<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        Gate::authorize('users.view');

        $query = User::query()->select('id', 'name', 'email', 'role_id', 'status')->with(['role', 'companies']);
        // ... rest of index ...
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        Gate::authorize('users.create');

        $roles = Role::all();
        $users = User::where('status', true)->get();
        $userLevels = ['Admin', 'Manager', 'Staff', 'Viewer'];
        $userTypes = ['Internal', 'External', 'Vendor'];
        $companies = Company::where('status', 'Active')->get();

        return view('users.create', compact('roles', 'users', 'userLevels', 'userTypes', 'companies'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('users.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role_id' => 'required|exists:roles,id',
            'companies' => 'required|array',
            'companies.*' => 'exists:companies,id',
            'status' => 'boolean',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
            'status' => $request->filled('status') ? true : false,
        ]);

        // Attach companies
        if (isset($validated['companies'])) {
            $user->companies()->attach($validated['companies']);
        }

        logActivity("Create User (ID - {$user->id}, Name - {$user->name}, Email - {$user->email})", 'CREATE', 'User', $user->id);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        Gate::authorize('users.edit');

        $roles = Role::all();
        $companies = Company::where('status', 'Active')->get();
        $users = User::where('status', true)->where('id', '!=', $user->id)->get();
        $userLevels = ['Admin', 'Manager', 'Staff', 'Viewer'];
        $userTypes = ['Internal', 'External', 'Vendor'];

        return view('users.edit', compact('user', 'roles', 'companies', 'users', 'userLevels', 'userTypes'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('users.edit');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable',
            'role_id' => 'required|exists:roles,id',
            'companies' => 'required|array',
            'companies.*' => 'exists:companies,id',
            'status' => 'boolean',
        ]);

        // Only update password if provided
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'status' => $request->filled('status') ? true : false,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        // Sync companies
        if (isset($validated['companies'])) {
            $user->companies()->sync($validated['companies']);
        }

        logActivity("Update User (ID - {$user->id}, Name - {$user->name}, Email - {$user->email})", 'UPDATE', 'User', $user->id);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete the specified user.
     */
    public function destroy(User $user)
    {
        Gate::authorize('users.delete');

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete your own account.');
        }

        logActivity("Delete User (ID - {$user->id}, Name - {$user->name}, Email - {$user->email})", 'DELETE', 'User', $user->id);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
