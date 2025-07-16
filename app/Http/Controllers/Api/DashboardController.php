<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\Extension;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function totalCommunicationsCount()
    {
        $count = Communication::count();

        return response()->json([
            'total_communications' => $count
        ]);
    }

    public function totalActiveExtensionsCount()
    {
        $count = Extension::where('status', 'active')
            ->whereNull('deleted_at')
            ->count();

        return response()->json([
            'total_active_extensions' => $count
        ]);
    }

    public function stats()
    {
        try {
            $trigger = request()->get('trigger');
            $daterange = request()->get('daterange');

            
            $data = globalHelper()->getDashboardData($trigger, $daterange);

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}