<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function __invoke(Request $request, BudgetService $budgetService): View
    {
        $month = $request->input('month', now()->format('Y-m'));

        return view('team.budgets.index', [
            'month' => $month,
            'rows' => $budgetService->utilizationForMonth($month),
        ]);
    }
}
