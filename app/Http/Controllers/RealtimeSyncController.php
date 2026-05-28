<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealtimeSyncController extends Controller
{
    /**
     * Get real-time status updates based on the authenticated user's role.
     */
    public function getUpdates()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $data = [
            'role' => $user->role,
        ];

        if ($user->role === 'admin') {
            $data['admin'] = [
                'pending_count' => WasteRequest::where('status', 'pending')->count(),
                'assigned_count' => WasteRequest::where('status', 'assigned')->count(),
                'completed_count' => WasteRequest::where('status', 'completed')->count(),
                'total_count' => WasteRequest::count(),
                // List of latest pending request IDs so we can compare and refresh
                'latest_pending_ids' => WasteRequest::where('status', 'pending')
                    ->latest()
                    ->pluck('id')
                    ->toArray(),
            ];
        } elseif ($user->role === 'worker') {
            $data['worker'] = [
                'assigned_count' => $user->assignedTasks()
                    ->whereIn('status', ['assigned', 'in_progress'])
                    ->count(),
                'latest_assigned_ids' => $user->assignedTasks()
                    ->whereIn('status', ['assigned', 'in_progress'])
                    ->latest()
                    ->pluck('id')
                    ->toArray(),
            ];
        } else {
            // For regular users
            $data['user'] = [
                'requests' => $user->wasteRequests()
                    ->select('id', 'status')
                    ->latest()
                    ->get()
                    ->toArray(),
            ];
        }

        return response()->json($data);
    }
}
