<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClaimStatusRequest;
use App\Models\Category;
use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function index(Request $request): View
    {
        $claims = ExpenseClaim::query()
            ->with(['submitter', 'category', 'reviewer'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->user_id))
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('claim_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('claim_date', '<=', $request->date_to))
            ->latest('claim_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.claims.index', [
            'claims' => $claims,
            'categories' => Category::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(ClaimStatusRequest $request, ExpenseClaim $claim): RedirectResponse
    {
        $status = $request->input('status');

        $claim->update([
            'status' => $status,
            'manager_comment' => $request->input('manager_comment'),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => $status === ExpenseClaim::STATUS_PENDING ? null : now(),
        ]);

        return redirect()->route('admin.claims.index')->with('success', 'Claim status updated.');
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
