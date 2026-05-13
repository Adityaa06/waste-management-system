<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssignedMail;

class AdminRequestController extends Controller
{
    /**
     * Display all waste requests for the admin.
     */
    public function index()
    {
        $requests = WasteRequest::with(['user', 'worker'])->latest()->get();
        $workers = User::where('role', 'worker')->get();
        return view('admin.requests.index', compact('requests', 'workers'));
    }

    /**
     * Assign a worker to a waste request.
     */
    public function assign(Request $request, WasteRequest $wasteRequest)
    {
        $request->validate([
            'worker_id' => 'required|exists:users,id,role,worker',
        ]);

        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $wasteRequest->update([
            'assigned_to' => $request->worker_id,
            'status' => 'assigned',
        ]);

        // Notify Worker
        $worker = User::find($request->worker_id);
        Mail::to($worker->email)->send(new TaskAssignedMail($wasteRequest));

        return redirect()->back()->with('success', 'Worker assigned successfully.');
    }
}
