<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseClaimRequest;
use App\Models\Category;
use App\Models\ExpenseClaim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseClaimController extends Controller
{
    public function index(Request $request): View
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

        return view('team.claims.index', [
            'claims' => $claims,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'statuses' => $this->statuses(),
            'summary' => $summary,
        ]);
    }

    public function create(): View
    {
        return view('team.claims.create', [
            'claim' => new ExpenseClaim(['claim_date' => now()->toDateString()]),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(ExpenseClaimRequest $request): RedirectResponse
    {
        $request->user()->expenseClaims()->create($request->validated() + [
            'status' => ExpenseClaim::STATUS_PENDING,
        ]);

        return redirect()->route('team.claims.index')->with('success', 'Expense claim submitted.');
    }

    public function edit(Request $request, ExpenseClaim $claim): View
    {
        $this->authorizeOwnPendingClaim($request, $claim);

        return view('team.claims.edit', [
            'claim' => $claim,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(ExpenseClaimRequest $request, ExpenseClaim $claim): RedirectResponse
    {
        $this->authorizeOwnPendingClaim($request, $claim);

        $claim->update($request->validated());

        return redirect()->route('team.claims.index')->with('success', 'Expense claim updated.');
    }

    public function destroy(Request $request, ExpenseClaim $claim): RedirectResponse
    {
        $this->authorizeOwnPendingClaim($request, $claim);

        $claim->delete();

        return redirect()->route('team.claims.index')->with('success', 'Expense claim deleted.');
    }

    private function authorizeOwnPendingClaim(Request $request, ExpenseClaim $claim): void
    {
        abort_if($claim->user_id !== $request->user()->id, 403);
        abort_if(! $claim->isPending(), 403);
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
