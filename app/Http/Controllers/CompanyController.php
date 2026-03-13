<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     */
    public function index(Request $request)
    {
        Gate::authorize('company.view');

        $search = $request->get('search', '');

        $companies = Company::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('pid', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        Gate::authorize('company.create');

        return view('companies.create');
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request)
    {
        Gate::authorize('company.create');

        $validated = $request->validate([
            'pid'          => 'required|string|max:50|unique:companies,pid',
            'name'         => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'designation'  => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'mobile'       => 'nullable|string|max:20',
            'territory'    => 'nullable|string|max:500',
            'status'       => 'required|in:Active,Inactive',
            'd_types'      => 'nullable|array',
            'd_parameter'  => 'nullable|array',
            'c_urls'       => 'nullable|array',
            'no_of_urls'   => 'nullable|integer|min:0|max:10',
        ]);

        if (isset($validated['d_types'])) {
            $validated['d_types'] = array_values(array_filter($validated['d_types']));
        }
        if (isset($validated['d_parameter'])) {
            $validated['d_parameter'] = array_values(array_filter($validated['d_parameter']));
        }
        if (isset($validated['c_urls'])) {
             $validated['c_urls'] = array_filter($validated['c_urls'], function($urlData) {
                 return !empty($urlData['url']);
             });
             $validated['c_urls'] = array_values($validated['c_urls']);
        }

        $company = Company::create($validated);

        logActivity("Create Company (ID - {$company->id}, Name - {$company->name})", 'CREATE', 'Company', $company->id);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        Gate::authorize('company.edit');

        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, Company $company)
    {
        Gate::authorize('company.edit');

        $validated = $request->validate([
            'pid'          => 'required|string|max:50|unique:companies,pid,' . $company->id,
            'name'         => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'designation'  => 'nullable|string|max:255',
            'email'        => 'nullable|email|max:255',
            'mobile'       => 'nullable|string|max:20',
            'territory'    => 'nullable|string|max:500',
            'status'       => 'required|in:Active,Inactive',
            'd_types'      => 'nullable|array',
            'd_parameter'  => 'nullable|array',
            'c_urls'       => 'nullable|array',
            'no_of_urls'   => 'nullable|integer|min:0|max:10',
        ]);

        if (isset($validated['d_types'])) {
            $validated['d_types'] = array_values(array_filter($validated['d_types']));
        }
        if (isset($validated['d_parameter'])) {
            $validated['d_parameter'] = array_values(array_filter($validated['d_parameter']));
        }
        
        if (isset($validated['c_urls'])) {
             $validated['c_urls'] = array_filter($validated['c_urls'], function($urlData) {
                 return !empty($urlData['url']);
             });
             $validated['c_urls'] = array_values($validated['c_urls']);
        }

        $company->update($validated);

        logActivity("Update Company (ID - {$company->id}, Name - {$company->name})", 'UPDATE', 'Company', $company->id);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company)
    {
        Gate::authorize('company.delete');

        logActivity("Delete Company (ID - {$company->id}, Name - {$company->name})", 'DELETE', 'Company', $company->id);

        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
