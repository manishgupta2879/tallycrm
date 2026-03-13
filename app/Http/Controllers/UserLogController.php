<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserLogController extends Controller
{
    /**
     * Display a listing of user logs.
     */
    public function index(Request $request)
    {
        Gate::authorize('activity-log.view');

        $search = $request->get('search', '');
        $userId = $request->get('user_id', '');
        $month = $request->get('month', '');
        $year = $request->get('year', '');
        
        $logs = UserLog::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when($month, function ($query, $month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query, $year) {
                $query->whereYear('date', $year);
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        $users = \App\Models\User::orderBy('name')->get();

        return view('user-logs.index', compact('logs', 'users'));
    }

    /**
     * Display the specified user log detail.
     */
    public function show(UserLog $userLog)
    {
        Gate::authorize('activity-log.view');

        $details = unserialize($userLog->detail) ?: [];
        return view('user-logs.show', compact('userLog', 'details'));
    }
}
