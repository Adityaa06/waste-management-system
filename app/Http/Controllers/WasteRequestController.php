<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WasteRequestController extends Controller
{
    /**
     * Display a listing of the user's waste requests.
     */
    public function index()
    {
        $requests = Auth::user()->wasteRequests()->latest()->get();
        return view('user.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new waste request.
     */
    public function create()
    {
        return view('user.requests.create');
    }

    /**
     * Store a newly created waste request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'waste_type' => 'required|in:dry,wet,mixed',
            'address' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('requests', 'public');
        }

        Auth::user()->wasteRequests()->create([
            'title' => $request->title,
            'description' => $request->description,
            'waste_type' => $request->waste_type,
            'address' => $request->address,
            'image' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
        ]);

        return redirect()->route('user.requests.index')->with('success', 'Waste request created successfully!');
    }

    /**
     * Remove the specified waste request from storage.
     */
    public function destroy(WasteRequest $request)
    {
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->image) {
            Storage::disk('public')->delete($request->image);
        }

        $request->delete();

        return redirect()->back()->with('success', 'Request deleted successfully.');
    }
}
