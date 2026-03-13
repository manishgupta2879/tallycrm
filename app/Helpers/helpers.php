<?php

use App\Models\UserLog;
use Illuminate\Support\Facades\DB;

if (!function_exists('isSuperAdmin')) {
    /**
     * Check if the currently authenticated user in an Admin/Super Admin.
     */
    function isSuperAdmin()
    {
        return auth()->check() && auth()->user()->isSuperAdmin();
    }
}

if (!function_exists('getUserLevel')) {
    /**
     * Get the role name of the currently authenticated user.
     */
    function getUserLevel()
    {
        return auth()->check() && auth()->user()->role ? auth()->user()->role->name : 'Viewer';
    }
}

if (!function_exists('logActivity')) {
    /**
     * Record user activity into a daily log entry with serialized details.
     * 
     * @param string $detail Human readable description of the action.
     * @param string $action Action type e.g., UPDATE, CREATE, LOGIN.
     * @param string|null $model Model name being affected.
     * @param mixed|null $modelId ID of the affected model.
     * @return array|bool
     */
    function logActivity($detail, $action = '', $model = null, $modelId = null)
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $date = date('Y-m-d');
            
            // Prepare the log entry metadata
            $logEntry = [
                'date'     => date('Y-m-d H:i:s'),
                'user'     => auth()->user()->name . ' (' . auth()->user()->email . ')',
                'role'     => isSuperAdmin() ? 'Super Admin' : getUserLevel(),
                'action'   => $action,
                'model'    => $model,
                'model_id' => $modelId,
                'detail'   => $detail,
            ];

            if ($action == 'LOGIN') {
                UserLog::create([
                    'user_id'       => $userId,
                    'date'          => $date,
                    'log_in'        => date('Y-m-d H:i:s'),
                    'last_activity' => date('Y-m-d H:i:s'),
                    'detail'        => serialize([$logEntry]),
                ]);
                return true;
            } elseif ($action == 'LOGOUT') {
                // Find the active log entry for today that hasn't been logged out yet
                $existingLog = UserLog::where('user_id', $userId)
                    ->where('date', $date)
                    ->whereNotNull('log_in')
                    ->whereNull('log_out')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($existingLog) {
                    $detailsArray = unserialize($existingLog->detail) ?: [];
                    $detailsArray[] = $logEntry;
                    
                    $existingLog->update([
                        'log_out' => date('Y-m-d H:i:s'),
                        'detail'  => serialize($detailsArray)
                    ]);
                }
                return true;
            }

            // Standard activities (Update, Create, Delete, etc.)
            $existingLog = UserLog::where('user_id', $userId)
                ->where('date', $date)
                ->whereNotNull('log_in')
                ->whereNull('log_out')
                ->orderBy('id', 'desc')
                ->first();

            if ($existingLog) {
                $detailsArray = unserialize($existingLog->detail) ?: [];
                $detailsArray[] = $logEntry;

                $existingLog->update([
                    'last_activity' => date('Y-m-d H:i:s'),
                    'detail'        => serialize($detailsArray),
                ]);
                return $detailsArray;
            } else {
                // If no active session found for today, create one (fallback case)
                $detailsArray = [$logEntry];
                UserLog::create([
                    'user_id'       => $userId,
                    'date'          => $date,
                    'last_activity' => date('Y-m-d H:i:s'),
                    'detail'        => serialize($detailsArray),
                ]);
                return $detailsArray;
            }
        }
        return false;
    }
}
