<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function cashFlowForecast()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        // Query the database view for the next 90 days
        $cashFlowData = DB::table('vw_landlord_cash_flow_forecast')
            ->orderBy('bills_due_date', 'ASC')
            ->get();

        // Calculate summary statistics
        $totalDue = $cashFlowData->sum('total_due');
        $totalCollected = $cashFlowData->sum('already_collected');
        $totalAtRisk = $cashFlowData->sum('amount_at_risk');
        $collectionRate = $totalDue > 0 ? round(($totalCollected / $totalDue) * 100, 2) : 0;

        // Count priority items
        $overdueDates = $cashFlowData->where('priority_flag', 'OVERDUE')->count();
        $criticalDates = $cashFlowData->where('priority_flag', 'CRITICAL')->count();

        return view('reports.cash-flow-forecast', [
            'cashFlowData' => $cashFlowData,
            'summary' => [
                'totalDue' => $totalDue,
                'totalCollected' => $totalCollected,
                'totalAtRisk' => $totalAtRisk,
                'collectionRate' => $collectionRate,
                'overdueDates' => $overdueDates,
                'criticalDates' => $criticalDates,
            ],
        ]);
    }
}
