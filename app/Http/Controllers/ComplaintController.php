<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the user's complaints.
     */
    public function index()
    {
        $complaints = Auth::user()->complaints()->latest()->get();
        return view('user.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new complaint.
     */
    public function create()
    {
        return view('user.complaints.create');
    }

    /**
     * Store a newly created complaint in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('complaints', 'public');
        }

        Auth::user()->complaints()->create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('user.complaints.index')->with('success', 'Complaint submitted successfully!');
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.complaints.show', compact('complaint'));
    }

    /**
     * Show the form for editing the specified complaint.
     */
    public function edit(Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.complaints.edit', compact('complaint'));
    }

    /**
     * Update the specified complaint in database.
     */
    public function update(Request $request, Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($complaint->image) {
                Storage::disk('public')->delete($complaint->image);
            }
            $data['image'] = $request->file('image')->store('complaints', 'public');
        }

        $complaint->update($data);

        return redirect()->route('user.complaints.index')->with('success', 'Complaint updated successfully!');
    }

    /**
     * Remove the specified complaint from database.
     */
    public function destroy(Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id()) {
            abort(403);
        }

        if ($complaint->image) {
            Storage::disk('public')->delete($complaint->image);
        }

        $complaint->delete();

        return redirect()->route('user.complaints.index')->with('success', 'Complaint deleted successfully.');
    }
}
