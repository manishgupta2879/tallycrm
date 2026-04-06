<?php

namespace App\Http\Controllers;

use App\Models\AdditionalOpportunity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdditionalOpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('additional-opportunities.view');

        $query = AdditionalOpportunity::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        $query->with('category'); // Eager load category relationship
        $AdditionalOpportunites = $query->paginate(20);
        return view('additional-opportunity.index', compact('AdditionalOpportunites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('additional-opportunities.create');
        $categories = Category::all();
        return view('additional-opportunity.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('additional-opportunities.create');

        $request->validate([
            'company_name' => 'required|string|max:255|unique:additional_opportunity,company_name',
            'category_id' => 'required|exists:category,id',
            'description' => 'nullable|string|max:250',
        ], [
            'company_name.required' => 'Company name is required.',
            'company_name.unique' => 'This company name already exists.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'description.max' => 'Description must not exceed 250 characters.',
        ]);

        AdditionalOpportunity::create($request->only(['company_name', 'category_id', 'description']));

        return redirect()->route('additional-opportunities.index')->with('success', 'Additional opportunity created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdditionalOpportunity $additionalOpportunity)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdditionalOpportunity $additionalOpportunity)
    {
        Gate::authorize('additional-opportunities.edit');
        $categories = Category::all();
        return view('additional-opportunity.edit', compact('additionalOpportunity', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdditionalOpportunity $additionalOpportunity)
    {
        Gate::authorize('additional-opportunities.edit');
        $request->validate([
            'company_name' => 'required|string|max:255|unique:additional_opportunity,company_name,' . $additionalOpportunity->id,
            'category_id' => 'required|exists:category,id',
            'description' => 'nullable|string|max:250',
        ], [
            'company_name.required' => 'Company name is required.',
            'company_name.unique' => 'This company name already exists.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is invalid.',
            'description.max' => 'Description must not exceed 250 characters.',
        ]);
        $additionalOpportunity->update($request->only(['company_name', 'category_id', 'description']));
        return redirect()->route('additional-opportunities.index')->with('success', 'Additional opportunity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdditionalOpportunity $additionalOpportunity)
    {
        Gate::authorize('additional-opportunities.delete');
        $additionalOpportunity->delete();
        return redirect()->route('additional-opportunities.index')->with('success', 'Additional opportunity deleted successfully.');
    }
}
