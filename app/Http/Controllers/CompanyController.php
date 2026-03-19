<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Services\CompanyService; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the companies.
     */
    public function index(Request $request)
    {
        Gate::authorize('company.view');

        $search = $request->get('search', '');

        $companies = Company::search($search)
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
    public function store(StoreCompanyRequest $request)
    {
        try {
            $this->companyService->createCompany($request->validated());
            return redirect()->route('companies.index')
                ->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
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
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            $urlsLoaded = $request->input('urls_loaded') === '1';
            $this->companyService->updateCompany($company, $request->validated(), $urlsLoaded);

            return redirect()->route('companies.index')
                ->with('success', 'Company updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt and return the URLs.
     */
    public function decryptUrls(Company $company)
    {
        Gate::authorize('company.edit');

        try {
            $rawUrls = $company->getRawOriginal('c_urls');
            $urls = $rawUrls ? json_decode(Crypt::decryptString($rawUrls), true) : [];
            return response()->json(['success' => true, 'urls' => $urls]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to decrypt URLs.'], 400);
        }
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company)
    {
        Gate::authorize('company.delete');

        try {
            $this->companyService->deleteCompany($company);
            return redirect()->route('companies.index')
                ->with('success', 'Company deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
