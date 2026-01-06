<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $totalChickens = Alert::distinct('chicken_id')->count('chicken_id');

        $abnormalChickens = Alert::where('behavior', 'Abnormal Behavior')
            ->distinct('chicken_id')
            ->count('chicken_id');

        $alerts = Alert::latest()->take(50)->get();

        return view('dashboard', compact(
            'totalChickens',
            'abnormalChickens',
            'alerts'
        ));
    }

    public function data(): JsonResponse
    {
        $totalChickens = Alert::distinct('chicken_id')->count('chicken_id');

        $abnormalChickens = Alert::where('behavior', 'Abnormal Behavior')
            ->distinct('chicken_id')
            ->count('chicken_id');

        $alerts = Alert::latest()->take(50)->get([
            'id', 'chicken_id', 'behavior', 'video_path', 'created_at'
        ]);

        return response()->json([
            'totalChickens' => $totalChickens,
            'abnormalChickens' => $abnormalChickens,
            'alerts' => $alerts,
        ]);
    }

}
