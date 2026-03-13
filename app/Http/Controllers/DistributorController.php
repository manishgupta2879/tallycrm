<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DistributorController extends Controller
{
    /**
     * Display a listing of the distributors.
     */
    public function index(Request $request)
    {
        Gate::authorize('distributor.view');

        $search = $request->get('search', '');

        $distributors = Distributor::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('pid', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('mobile', 'like', "%{$search}%");
            })
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

        $companies = Company::orderBy('name')->get();
        return view('distributors.create', compact('companies'));
    }

    /**
     * Store a newly created distributor.
     */
    public function store(Request $request)
    {
        Gate::authorize('distributor.create');

        $validated = $request->validate([
            'pid'                   => 'required|string|max:50|unique:distributors,pid',
            'name'                  => 'required|string|max:255',
            'distributor_type'      => 'nullable|string|max:255',
            'company_pid'           => 'nullable|string|max:50',
            'address'               => 'nullable|string',
            'city'                  => 'nullable|string|max:100',
            'state'                 => 'nullable|string|max:100',
            'pin_code'              => 'nullable|string|max:20',
            'gst_no'                => 'nullable|string|max:50',
            'pan_no'                => 'nullable|string|max:20',
            'contact_name'          => 'nullable|string|max:255',
            'designation'           => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'mobile'                => 'nullable|string|max:20',
            'distributor_location'  => 'nullable|string|max:500',
            'status'                => 'required|in:Active,Inactive',
            'c_urls'                => 'nullable|array',
        ]);

        // Collect parameters into JSON
        $d_parameters = [];
        for ($i = 1; $i <= 10; $i++) {
            if ($request->has("d_parameter_$i")) {
                $d_parameters[$i] = $request->input("d_parameter_$i");
            }
        }
        $validated['d_parameters'] = $d_parameters;

        $distributor = Distributor::create($validated);

        logActivity("Create Distributor (ID - {$distributor->id}, Name - {$distributor->name})", 'CREATE', 'Distributor', $distributor->id);

        return redirect()->route('distributors.index')
            ->with('success', 'Distributor created successfully.');
    }

    /**
     * Show the form for editing the specified distributor.
     */
    public function edit(Distributor $distributor)
    {
        Gate::authorize('distributor.edit');

        $companies = Company::orderBy('name')->get();
        return view('distributors.edit', compact('distributor', 'companies'));
    }

    /**
     * Update the specified distributor.
     */
    public function update(Request $request, Distributor $distributor)
    {
        Gate::authorize('distributor.edit');

        $validated = $request->validate([
            'pid'                   => 'required|string|max:50|unique:distributors,pid,' . $distributor->id,
            'name'                  => 'required|string|max:255',
            'distributor_type'      => 'nullable|string|max:255',
            'company_pid'           => 'nullable|string|max:50',
            'address'               => 'nullable|string',
            'city'                  => 'nullable|string|max:100',
            'state'                 => 'nullable|string|max:100',
            'pin_code'              => 'nullable|string|max:20',
            'gst_no'                => 'nullable|string|max:50',
            'pan_no'                => 'nullable|string|max:20',
            'contact_name'          => 'nullable|string|max:255',
            'designation'           => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'mobile'                => 'nullable|string|max:20',
            'distributor_location'  => 'nullable|string|max:500',
            'status'                => 'required|in:Active,Inactive',
            'c_urls'                => 'nullable|array',
        ]);

        // Collect parameters into JSON
        $d_parameters = [];
        for ($i = 1; $i <= 10; $i++) {
            if ($request->has("d_parameter_$i")) {
                $d_parameters[$i] = $request->input("d_parameter_$i");
            }
        }
        $validated['d_parameters'] = $d_parameters;

        $distributor->update($validated);

        logActivity("Update Distributor (ID - {$distributor->id}, Name - {$distributor->name})", 'UPDATE', 'Distributor', $distributor->id);

        return redirect()->route('distributors.index')
            ->with('success', 'Distributor updated successfully.');
    }

    /**
     * Remove the specified distributor.
     */
    public function destroy(Distributor $distributor)
    {
        Gate::authorize('distributor.delete');

        logActivity("Delete Distributor (ID - {$distributor->id}, Name - {$distributor->name})", 'DELETE', 'Distributor', $distributor->id);
        
        $distributor->delete();

        return redirect()->route('distributors.index')
            ->with('success', 'Distributor deleted successfully.');
    }

    public function getCompanyDetails($pid)
    {
        $company = Company::where('pid', $pid)->first();

        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        // Map parameter labels to 1-10 numbering for the distributor columns
        $parameters = [];
        if (is_array($company->d_parameter)) {
            foreach ($company->d_parameter as $index => $label) {
                if ($index < 10) {
                    $parameters[$index + 1] = $label;
                }
            }
        }

        return response()->json([
            'distributor_types' => $company->d_types ?? [],
            'parameters' => (object)$parameters,
            'c_urls' => $company->c_urls ?? []
        ]);
    }
}
