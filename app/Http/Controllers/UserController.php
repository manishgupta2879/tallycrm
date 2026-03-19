<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService; // Added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = $request->filled('status');
            
            $this->userService->createUser($data);

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
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
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            $data['status'] = $request->filled('status');

            $this->userService->updateUser($user, $data);

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
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

        try {
            $this->userService->deleteUser($user);
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
