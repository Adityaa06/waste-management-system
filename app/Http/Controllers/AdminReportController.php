<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    /**
     * Display filtered waste request reports.
     */
    public function index(Request $request)
    {
        $workers = User::where('role', 'worker')->get();
        $query = $this->buildFilterQuery($request);
        $requests = $query->latest()->get();

        return view('admin.reports.index', [
            'requests' => $requests,
            'workers' => $workers,
            'filters' => $request->only(['start_date', 'end_date', 'worker_id', 'status', 'waste_type'])
        ]);
    }

    /**
     * Export the filtered reports to a downloadable CSV file.
     */
    public function export(Request $request)
    {
        $query = $this->buildFilterQuery($request);
        $requests = $query->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=waste_report_" . date('Ymd_His') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Request ID', 
            'User Name', 
            'User Email', 
            'Title', 
            'Waste Type', 
            'Status', 
            'Assigned Worker', 
            'Collection Address', 
            'Requested At', 
            'Completed At'
        ];

        $callback = function() use ($requests, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($requests as $req) {
                fputcsv($file, [
                    $req->id,
                    $req->user->name,
                    $req->user->email,
                    $req->title,
                    $req->waste_type,
                    $req->status,
                    $req->worker ? $req->worker->name : 'Unassigned',
                    $req->address,
                    $req->created_at->toDateTimeString(),
                    $req->completed_at ? $req->completed_at->toDateTimeString() : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Build the query based on request filters.
     */
    private function buildFilterQuery(Request $request)
    {
        $query = WasteRequest::with(['user', 'worker']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }

        // Worker filter
        if ($request->filled('worker_id')) {
            $query->where('assigned_to', $request->worker_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Waste Type filter
        if ($request->filled('waste_type')) {
            $query->where('waste_type', $request->waste_type);
        }

        return $query;
    }
}
