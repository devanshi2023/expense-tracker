<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewClaimRequest;
use App\Models\ExpenseClaim;
use App\Services\BudgetService;
use Illuminate\Http\RedirectResponse;

class ClaimReviewController extends Controller
{
    public function update(ReviewClaimRequest $request, ExpenseClaim $claim, BudgetService $budgetService): RedirectResponse
    {
        abort_if($claim->user_id === $request->user()->id, 403);
        abort_if(! $claim->isPending(), 403);

        $status = $request->input('status');
        $warning = ['exceeds' => false, 'message' => null];

        if ($status === ExpenseClaim::STATUS_APPROVED) {
            $claim->load('category');
            $warning = $budgetService->approvalWarning($claim);
        }

        $claim->update([
            'status' => $status,
            'manager_comment' => $request->input('manager_comment'),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $redirect = redirect()
            ->route('manager.dashboard')
            ->with('success', 'Claim '.$status.'.');

        if ($warning['exceeds']) {
            $redirect->with('warning', $warning['message']);
        }

        return $redirect;
    }
}
