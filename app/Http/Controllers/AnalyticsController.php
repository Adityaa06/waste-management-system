<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function getStats()
    {
        // 1. Waste Type Distribution
        $wasteTypeData = WasteRequest::select('waste_type', DB::raw('count(*) as total'))
            ->groupBy('waste_type')
            ->get();

        // 2. Status Overview
        $statusData = WasteRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 3. Daily Trend (Last 7 days)
        $dailyTrend = WasteRequest::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'wasteTypes' => $wasteTypeData,
            'status' => $statusData,
            'trend' => $dailyTrend,
        ]);
    }
}
