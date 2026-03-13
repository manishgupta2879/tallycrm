<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('categories.view');

        $query = Category::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $categorys = $query->paginate(20);
        
        return view('category.index', compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('categories.create');

        // Get permissions organized hierarchically
        // $permissionsHierarchy = $this->getHierarchicalPermissions();

        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('categories.create');

        $request->validate([
            'name' => 'required|string|max:255|unique:category,name',
        ], [
            'name.required' => 'Category name is required.',
            'name.unique' => 'This category name already exists.',
        ]);

        $category = Category::create($request->only(['name']));

        // Save permissions
        // $this->saveRolePermissions($category, $request);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Gate::authorize('categories.create');
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        Gate::authorize('categories.edit');
        $request->validate([
            'name' => 'required|string|max:255|unique:category,name,' . $category->id,
        ], [
            'name.required' => 'Category name is required.',
            'name.unique' => 'This category name already exists.',
        ]);
        $category->update($request->only(['name']));
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('categories.delete');
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
