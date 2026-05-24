<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ExpenseClaim;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, BudgetService $budgetService): View
    {
        $pendingClaims = ExpenseClaim::query()
            ->with(['submitter', 'category'])
            ->where('status', ExpenseClaim::STATUS_PENDING)
            ->latest('claim_date')
            ->get();

        $processedClaims = ExpenseClaim::query()
            ->with(['submitter', 'category', 'reviewer'])
            ->whereIn('status', [ExpenseClaim::STATUS_APPROVED, ExpenseClaim::STATUS_REJECTED])
            ->latest('reviewed_at')
            ->limit(25)
            ->get();

        return view('manager.dashboard', [
            'pendingClaims' => $pendingClaims,
            'processedClaims' => $processedClaims,
            'budgetRows' => $budgetService->utilizationForMonth(now()->format('Y-m')),
        ]);
    }
}
