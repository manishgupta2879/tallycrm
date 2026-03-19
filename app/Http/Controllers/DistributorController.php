<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Company;
use App\Models\Geo;
use App\Models\Contact;
use App\Http\Requests\StoreDistributorRequest;
use App\Http\Requests\UpdateDistributorRequest;
use App\Services\DistributorService; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DistributorController extends Controller
{
    protected $distributorService;

    public function __construct(DistributorService $distributorService)
    {
        $this->distributorService = $distributorService;
    }

    /**
     * Display a listing of the distributors.
     */
    public function index(Request $request)
    {
        Gate::authorize('distributor.view');

        $search = $request->input('search');

        $distributors = Distributor::with(['contacts', 'company', 'geoRegion', 'geoState', 'geoCity'])
            ->search($search)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('distributors.index', compact('distributors'));
    }

    /**
     * Show the form for creating a new distributor.
     */
    public function create()
    {
        Gate::authorize('distributor.create');
        $companies = Company::where('status', 'Active')->get();
        $countries = Geo::where('nature', 'Country')->get();
        $deploymentOptions = ['local' => 'Local', 'cloud' => 'Cloud'];
        $statusOptions = ['Active' => 'Active', 'Inactive' => 'Inactive'];
        return view('distributors.create', compact('companies', 'countries', 'deploymentOptions', 'statusOptions'));
    }

    /**
     * Store a newly created distributor.
     */
    public function store(StoreDistributorRequest $request)
    {
        try {
            $this->distributorService->createDistributor($request->validated());
            return redirect()->route('distributors.index')->with('success', 'Distributor created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified distributor.
     */
    public function edit($id)
    {
        Gate::authorize('distributor.edit');

        $distributor = Distributor::with('contacts')->findOrFail($id);

        return view('distributors.edit', compact('distributor'));
    }

    /**
     * Update the specified distributor in storage.
     */
    public function update(UpdateDistributorRequest $request, $id)
    {
        $distributor = Distributor::findOrFail($id);

        try {
            $this->distributorService->updateDistributor($distributor, $request->validated());
            return redirect()->route('distributors.index')->with('success', 'Distributor updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified distributor from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('distributor.delete');

        $distributor = Distributor::findOrFail($id);

        try {
            $this->distributorService->deleteDistributor($distributor);
            return redirect()->route('distributors.index')->with('success', 'Distributor deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // AJAX helper for company details
    public function getCompanyDetails($pid)
    {
        $company = Company::where('pid', $pid)->first();
        if (!$company)
            return response()->json(['error' => 'Not found'], 404);

        return response()->json([
            'distributor_types' => $company->d_types ?? [],
            'parameters' => $company->d_parameter ?? []
        ]);
    }

    // AJAX helpers for Geo
    public function getRegions($countryId)
    {
        return response()->json(Geo::where('nature', 'Region')->where('rid', $countryId)->orderBy('name')->get(['id', 'name']));
    }

    public function getStates($regionId)
    {
        return response()->json(Geo::where('nature', 'State')->where('rid', $regionId)->orderBy('name')->get(['id', 'name']));
    }

    public function getCities($stateId)
    {
        return response()->json(Geo::where('nature', 'City')->where('rid', $stateId)->orderBy('name')->get(['id', 'name']));
    }
}
