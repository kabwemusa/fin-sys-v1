<?php

namespace App\Livewire\Admin;

use App\Models\LoanApplication;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $now = now();

        $totalPending    = LoanApplication::where('status', 'pending')->count();
        $totalDisbursed  = LoanApplication::where('status', 'disbursed')
            ->whereMonth('disbursed_at', $now->month)
            ->whereYear('disbursed_at', $now->year)
            ->sum('amount_approved');

        // Processed = approved + rejected this month
        $approvedCount  = LoanApplication::where('status', 'approved')->whereMonth('created_at', $now->month)->count();
        $rejectedCount  = LoanApplication::where('status', 'rejected')->whereMonth('created_at', $now->month)->count();
        $processedTotal = $approvedCount + $rejectedCount;
        $approvalRate   = $processedTotal > 0 ? round($approvedCount / $processedTotal * 100) : 0;

        // Overdue: disbursed, due_date past, has outstanding balance
        $overdue = LoanApplication::where('status', 'disbursed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', $now->toDateString())
            ->count();

        // Applications by month (last 6 months)
        $monthlyStats = LoanApplication::select(
            DB::raw("TO_CHAR(created_at, 'Mon') as month"),
            DB::raw("DATE_TRUNC('month', created_at) as month_start"),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'approved' OR status = 'disbursed' THEN 1 ELSE 0 END) as approved")
        )
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('month_start', 'month')
            ->orderBy('month_start')
            ->get();

        $recentApplications = LoanApplication::with(['customer.user', 'loanProduct'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', compact(
            'totalPending', 'totalDisbursed', 'overdue', 'approvalRate',
            'monthlyStats', 'recentApplications'
        ))->layout('components.layouts.admin', ['title' => 'Dashboard']);
    }
}
