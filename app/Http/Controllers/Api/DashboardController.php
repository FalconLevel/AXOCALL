<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

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

    public function export()
    {
        try {
            $daterange = request()->get('daterange');

            $data = globalHelper()->getDashboardData("", $daterange);
            // return response()->json($data);
            $filename = 'dashboard_'.date('Y-m-d_H-i-s').'.csv';
            $path = public_path('assets/axocall/exports/'.$filename);
            $file = fopen($path, 'w');
            fputcsv($file, [
                'Total Communications', 
                'Total Messages', 
                'Total Extensions', 
                'Total Follow Ups', 
                'Total Appointments Booked', 
                'Total Calls with Sentiment', 
                'Total Positive Calls', 
                'Total Neutral Calls', 
                'Total Negative Calls', 
                'Total Calls by Hour',
                'Total Keywords Hits',
                'Total Keywords Missed',
                'Overall Keywords Hit Rate']);
            fputcsv($file, [
                $data['total_communications'],
                $data['total_messages'],
                $data['total_extensions'],
                $data['total_follow_ups'],
                $data['total_appointments_booked'],
                $data['total_calls_with_sentiment'],
                $data['total_positive_calls'],
                $data['total_neutral_calls'],
                $data['total_negative_calls'],
                array_sum($data['total_calls_by_hour']),
                array_sum($data['keywords_hits']),
                $data['keywords_missed'],
                $data['overall_keywords_hit_rate'],
            ]);
            fclose($file);

            return response()->json(['status' => true, 'data' => URL::to('assets/axocall/exports/'.$filename)]);
        } catch (\Exception $e) {
            logInfo($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}