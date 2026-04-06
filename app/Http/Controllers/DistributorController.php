<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Company;
use App\Models\Geo;
use App\Models\Contact;
use App\Models\TallyLog;
use App\Models\TdlAddon;
use App\Models\CompanyFeature;
use App\Models\DistributorParameter;
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
        $companies = Company::where('status', '=', 'Active')->get();
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
        $companies = Company::where('status', '=', 'Active', 'and')->get();
        $countries = Geo::where('nature', '=', 'Country')->get();
        $deploymentOptions = ['local' => 'Local', 'cloud' => 'Cloud'];
        $statusOptions = ['Active' => 'Active', 'Inactive' => 'Inactive'];

        return view('distributors.edit', compact('distributor', 'companies', 'countries', 'deploymentOptions', 'statusOptions'));
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
        $company = Company::where('pid', '=', $pid)->first();
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
        return response()->json(Geo::where('nature', '=', 'Region', 'and')->where('rid', '=', $countryId, 'and')->orderBy('name')->get(['id', 'name']));
    }

    public function getStates($regionId)
    {
        return response()->json(Geo::where('nature', '=', 'State', 'and')->where('rid', '=', $regionId, 'and')->orderBy('name')->get(['id', 'name']));
    }

    public function getCities($stateId)
    {
        return response()->json(Geo::where('nature', '=', 'City', 'and')->where('rid', '=', $stateId, 'and')->orderBy('name')->get(['id', 'name']));
    }

    public function tallyDetails(Distributor $distributor)
    {
        Gate::authorize('distributor.view');

        $logs = TallyLog::where('tally_serial_no', '=', $distributor->tally_serial, 'and')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('distributors.tally_details', compact('distributor', 'logs'));
    }

    public function tdlAddons(Distributor $distributor)
    {
        Gate::authorize('distributor.view');

        $addons = TdlAddon::where('tally_serial_no', $distributor->tally_serial)
            ->orderBy('batch_id', 'desc')
            ->orderBy('tcp_filename', 'asc')
            ->paginate(20);

        $latestBatchId = TdlAddon::where('tally_serial_no', $distributor->tally_serial)->max('batch_id');

        return view('distributors.tdl_addons', compact('distributor', 'addons', 'latestBatchId'));
    }

    public function companyFeatures(Distributor $distributor)
    {
        Gate::authorize('distributor.view');

        $features = CompanyFeature::where('tally_serial_no', $distributor->tally_serial)
            ->where('dist_name', $distributor->name)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('distributors.company_features', compact('distributor', 'features'));
    }

    /**
     * Display the additional parameters for a distributor.
     */
    public function showParameters(Distributor $distributor)
    {
        Gate::authorize('distributor.view');
        $parameters = DistributorParameter::where('tallyserialno', $distributor->tally_serial)
            ->where('principalid', $distributor->company_code)
            ->where('distributorid', $distributor->code)
            ->where('distname', $distributor->name)
            ->orderBy('created_at', 'desc')
            ->get();
        $parameterNames = $distributor?->company?->d_parameter ?? [];
        return view('distributors.parameters', compact('distributor', 'parameters', 'parameterNames'));
    }

    /**
     * Show the form for editing a parameter.
     */
    public function editParameter(Distributor $distributor, DistributorParameter $parameter)
    {
        Gate::authorize('distributor.update');

        return view('distributors.parameters-edit', compact('distributor', 'parameter'));
    }

    /**
     * Update a specific parameter record.
     */
    public function updateParameter(Request $request, Distributor $distributor, DistributorParameter $parameter)
    {
        Gate::authorize('distributor.update');

        $validated = $request->validate([
            'p1' => 'nullable|string',
            'p2' => 'nullable|string',
            'p3' => 'nullable|string',
            'p4' => 'nullable|string',
            'p5' => 'nullable|string',
            'p6' => 'nullable|string',
            'p7' => 'nullable|string',
            'p8' => 'nullable|string',
            'p9' => 'nullable|string',
            'p10' => 'nullable|string',
        ]);

        $parameter->update($validated);

        return redirect()->route('distributors.parameters', $distributor->id)
            ->with('success', 'Parameter updated successfully!');
    }
}
