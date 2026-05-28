<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'worker') {
            return redirect()->route('worker.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function admin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        // Fetch real admin overview stats
        $totalUsers = User::count();
        $totalRequests = WasteRequest::count();
        $pendingRequests = WasteRequest::where('status', 'pending')->count();
        $completedRequests = WasteRequest::where('status', 'completed')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRequests',
            'pendingRequests',
            'completedRequests'
        ));
    }

    public function worker()
    {
        if (Auth::user()->role !== 'worker') {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        // Fetch real worker stats
        $assignedTasks = $user->assignedTasks()->where('status', 'assigned')->count();
        $completedTasks = $user->assignedTasks()->where('status', 'completed')->count();

        // Fetch real assigned tasks (today's/active tasks)
        $todayTasks = $user->assignedTasks()->where('status', 'assigned')->latest()->take(5)->get();

        return view('worker.dashboard', compact(
            'assignedTasks',
            'completedTasks',
            'todayTasks'
        ));
    }

    public function user()
    {
        if (Auth::user()->role !== 'user') {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        // Fetch real user stats from database
        $totalRequests = $user->wasteRequests()->count();
        $inProgressRequests = $user->wasteRequests()->whereIn('status', ['pending', 'assigned'])->count();
        $completedRequests = $user->wasteRequests()->where('status', 'completed')->count();

        return view('user.dashboard', compact(
            'totalRequests',
            'inProgressRequests',
            'completedRequests'
        ));
    }
}
