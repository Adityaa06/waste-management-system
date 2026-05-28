<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all users (users and workers).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $query = User::query();

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        // Status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    /**
     * Store a newly created Worker user in the database.
     */
    public function storeWorker(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive',
            'assigned_area' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'worker',
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => $request->status,
            'assigned_area' => $request->assigned_area,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Worker account created successfully!');
    }

    /**
     * Update worker details in the database.
     */
    public function updateWorker(Request $request, User $worker)
    {
        if ($worker->role !== 'worker') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($worker->id)],
            'password' => 'nullable|string|min:8',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|in:active,inactive',
            'assigned_area' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => $request->status,
            'assigned_area' => $request->assigned_area,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $worker->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Worker account updated successfully!');
    }

    /**
     * Toggle the status of a user (activate/deactivate).
     */
    public function toggleStatus(User $user)
    {
        // Don't allow toggling own status
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $msg = $user->role === 'worker' ? 'Worker' : 'User';
        return redirect()->route('admin.users.index')->with('success', "{$msg} account status updated to {$newStatus}.");
    }

    /**
     * Delete a user or worker.
     */
    public function destroy(User $user)
    {
        // Don't allow deleting self
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Account deleted successfully.');
    }
}
