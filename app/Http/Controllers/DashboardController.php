<?php

namespace App\Http\Controllers;

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
        return view('admin.dashboard');
    }

    public function worker()
    {
        if (Auth::user()->role !== 'worker') {
            return redirect()->route('dashboard');
        }
        return view('worker.dashboard');
    }

    public function user()
    {
        if (Auth::user()->role !== 'user') {
            return redirect()->route('dashboard');
        }
        return view('user.dashboard');
    }
}
