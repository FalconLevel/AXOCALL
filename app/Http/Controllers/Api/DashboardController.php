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
}