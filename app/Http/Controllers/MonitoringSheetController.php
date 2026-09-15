<?php

namespace App\Http\Controllers;

use App\Models\MonitoringSheet;

class MonitoringSheetController extends Controller
{
    public function show(MonitoringSheet $monitoringSheet)
    {
        return response()->json($monitoringSheet->load('pressWork'));
    }
}
