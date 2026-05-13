<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCompletedMail;

class WorkerTaskController extends Controller
{
    /**
     * Display tasks assigned to the worker.
     */
    public function index()
    {
        $tasks = Auth::user()->assignedTasks()->latest()->get();
        return view('worker.tasks.index', compact('tasks'));
    }

    /**
     * Mark a task as completed.
     */
    public function complete(WasteRequest $wasteRequest)
    {
        if ($wasteRequest->assigned_to !== Auth::id()) {
            abort(403);
        }

        $wasteRequest->update([
            'status' => 'completed',
        ]);

        // Notify User
        Mail::to($wasteRequest->user->email)->send(new TaskCompletedMail($wasteRequest));

        return redirect()->back()->with('success', 'Task marked as completed!');
    }
}
