<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ExpenseClaim;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $claims = ExpenseClaim::query()
            ->with('category')
            ->where('user_id', $request->user()->id)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->latest('claim_date')
            ->paginate(10)
            ->withQueryString();

        $summary = ExpenseClaim::query()
            ->where('user_id', $request->user()->id)
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when status = ? then 1 else 0 end) as pending", [ExpenseClaim::STATUS_PENDING])
            ->selectRaw("sum(case when status = ? then 1 else 0 end) as approved", [ExpenseClaim::STATUS_APPROVED])
            ->selectRaw("sum(case when status = ? then 1 else 0 end) as rejected", [ExpenseClaim::STATUS_REJECTED])
            ->first();

        return view('team.dashboard', [
            'claims' => $claims,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'statuses' => $this->statuses(),
            'summary' => $summary,
        ]);
    }

    private function statuses(): array
    {
        return [
            ExpenseClaim::STATUS_PENDING => 'Pending',
            ExpenseClaim::STATUS_APPROVED => 'Approved',
            ExpenseClaim::STATUS_REJECTED => 'Rejected',
        ];
    }
}
