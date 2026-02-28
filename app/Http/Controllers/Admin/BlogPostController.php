<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Author;
use App\Models\Category;
use App\Services\HtmlProcessor;
use App\Services\SimplePurifier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display blog posts list
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('author', 'category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        }

        // Filter by author
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate(15);
        $authors = Author::active()->get();
        $categories = Category::active()->get();

        return view('admin.blog.index', [
            'posts' => $posts,
            'authors' => $authors,
            'categories' => $categories,
            'search' => $request->search,
            'selectedAuthor' => $request->author_id,
            'selectedCategory' => $request->category_id,
            'selectedStatus' => $request->status,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $authors = Author::active()->get();
        $categories = Category::active()->get();

        return view('admin.blog.create', [
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    /**
     * Store blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|max:20000',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        // Sanitize content with SimplePurifier (using custom_editor profile)
        $validated['content'] = SimplePurifier::clean($validated['content'], 'custom_editor');

        // Ensure external links open in new tab with proper rel attribute
        $validated['content'] = HtmlProcessor::externalLinksNewTab($validated['content']);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blog', 'public');
            $validated['featured_image'] = $path;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);

        // Set published_at if published
        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        return redirect()->route('admin.blog.show', $post)->with('success', 'Blog post berhasil dibuat');
    }

    /**
     * Show blog post detail
     */
    public function show(BlogPost $post)
    {
        $post->load('author', 'category');

        return view('admin.blog.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(BlogPost $post)
    {
        $authors = Author::active()->get();
        $categories = Category::active()->get();

        return view('admin.blog.edit', [
            'post' => $post,
            'authors' => $authors,
            'categories' => $categories,
        ]);
    }

    /**
     * Update blog post
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|max:20000',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        // Sanitize content with SimplePurifier (using custom_editor profile)
        $validated['content'] = SimplePurifier::clean($validated['content'], 'custom_editor');

        // Ensure external links open in new tab with proper rel attribute
        $validated['content'] = HtmlProcessor::externalLinksNewTab($validated['content']);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $path = $request->file('featured_image')->store('blog', 'public');
            $validated['featured_image'] = $path;
        }

        // Generate slug if title changed
        if ($validated['title'] !== $post->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Set published_at
        if ($request->boolean('is_published') && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif (!$request->boolean('is_published')) {
            $validated['published_at'] = null;
        }

        $post->update($validated);

        return redirect()->route('admin.blog.show', $post)->with('success', 'Blog post berhasil diupdate');
    }

    /**
     * Delete blog post
     */
    public function destroy(BlogPost $post)
    {
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post berhasil dihapus');
    }

    /**
     * Publish blog post
     */
    public function publish(BlogPost $post)
    {
        $post->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Blog post dipublikasikan');
    }

    /**
     * Unpublish blog post
     */
    public function unpublish(BlogPost $post)
    {
        $post->update([
            'is_published' => false,
            'published_at' => null,
        ]);

        return back()->with('success', 'Blog post tidak lagi dipublikasikan');
    }
}
