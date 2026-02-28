<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    /**
     * Display authors list
     */
    public function index(Request $request)
    {
        $query = Author::withCount('books', 'blogPosts');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $authors = $query->orderBy('name')->paginate(15);

        return view('admin.authors.index', [
            'authors' => $authors,
            'search' => $request->search,
            'selectedStatus' => $request->status,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.authors.create');
    }

    /**
     * Store author
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors',
            'email' => 'nullable|email|unique:authors',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('authors', 'public');
            $validated['photo'] = $path;
        }

        // Parse social media if provided (only non-empty fields)
        $socialMedia = [];
        foreach (['twitter', 'instagram', 'facebook', 'tiktok', 'website'] as $platform) {
            if ($request->filled("social_media.{$platform}")) {
                $socialMedia[$platform] = $request->input("social_media.{$platform}");
            }
        }
        if (!empty($socialMedia)) {
            $validated['social_media'] = $socialMedia;
        }

        $validated['slug'] = Str::slug($validated['name']);

        $author = Author::create($validated);

        return redirect()->route('admin.authors.show', $author)->with('success', 'Author berhasil ditambahkan');
    }

    /**
     * Show author detail
     */
    public function show(Author $author)
    {
        $author->load(['books', 'blogPosts']);

        return view('admin.authors.show', [
            'author' => $author,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Author $author)
    {
        return view('admin.authors.edit', [
            'author' => $author,
        ]);
    }

    /**
     * Update author
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:authors,name,' . $author->id,
            'email' => 'nullable|email|unique:authors,email,' . $author->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle photo
        if ($request->hasFile('photo')) {
            if ($author->photo) {
                \Storage::disk('public')->delete($author->photo);
            }
            $path = $request->file('photo')->store('authors', 'public');
            $validated['photo'] = $path;
        }

        // Parse social media if provided (only non-empty fields)
        $socialMedia = [];
        foreach (['twitter', 'instagram', 'facebook', 'tiktok', 'website'] as $platform) {
            if ($request->filled("social_media.{$platform}")) {
                $socialMedia[$platform] = $request->input("social_media.{$platform}");
            }
        }
        if (!empty($socialMedia)) {
            $validated['social_media'] = $socialMedia;
        }

        if ($validated['name'] !== $author->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $author->update($validated);

        return redirect()->route('admin.authors.show', $author)->with('success', 'Author berhasil diupdate');
    }

    /**
     * Delete author
     */
    public function destroy(Author $author)
    {
        if ($author->photo) {
            \Storage::disk('public')->delete($author->photo);
        }

        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author berhasil dihapus');
    }
}
