<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display categories list
     */
    public function index(Request $request)
    {
        $query = Category::withCount('books');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->orderBy('name')->paginate(15);

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $request->search,
            'selectedStatus' => $request->status,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return redirect()->route('admin.categories.show', $category)->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show category detail
     */
    public function show(Category $category)
    {
        $category->load('books');

        return view('admin.categories.show', [
            'category' => $category,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validated['name'] !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.show', $category)->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        if ($category->books()->count() > 0) {
            return back()->withErrors(['error' => 'Tidak bisa menghapus kategori yang memiliki buku']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
