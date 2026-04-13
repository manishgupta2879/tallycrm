<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TallyLog;
use Illuminate\Support\Facades\Gate;

class TallyLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('tally-log.view');

        $query = TallyLog::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereAny([
                'tally_serial_no',
                'tally_version',
                'account_id',
                'tally_edition'
            ], 'like', "%{$search}%");
        }

        $tallyLogs = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('tally-logs.index', compact('tallyLogs'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TallyLog $tallyLog)
    {
        Gate::authorize('tally-log.delete');
        $tallyLog->delete();

        return redirect()->route('tally-logs.index')->with('success', 'Log deleted successfully');
    }
}
