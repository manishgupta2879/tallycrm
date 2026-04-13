<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\ValidateDecryptPasswordRequest;
use App\Services\CompanyService; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

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
            ->withCount('distributors')
            ->orderBy('name')
            ->paginate(25)
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

    public function validateDecryptPassword(ValidateDecryptPasswordRequest $request, Company $company)
    {
        Gate::authorize('company.edit');
        if (!$request->validatePassword()) {
            return response()->json(['success' => false, 'message' => 'Invalid password.'], 403);
        }

        return response()->json(['success' => true, 'message' => 'Password validated successfully.']);
    }

    /**
     * Decrypt and return the URLs.
     * This should only be called after password validation.
     */
    public function decryptUrls(Company $company)
    {
        Gate::authorize('company.edit');

        try {
            $urls = $company->c_urls;

            // Handle legacy double-encrypted data if still present
            if (is_string($urls)) {
                $urls = json_decode(Crypt::decryptString($urls), true);
            }

            // Ensure we return an array/object, not null
            if (empty($urls)) {
                return response()->json(['success' => true, 'urls' => []]);
            }

            return response()->json(['success' => true, 'urls' => $urls]);
        } catch (\Exception $e) {
            \Log::error('URL Decryption Error for Company ' . $company->id . ': ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to decrypt URLs. Please try again later.'], 400);
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
