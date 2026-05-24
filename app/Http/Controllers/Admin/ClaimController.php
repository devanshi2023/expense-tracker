<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClaimStatusRequest;
use App\Mail\ClaimReviewedMail;
use App\Models\Category;
use App\Models\ExpenseClaim;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClaimController extends Controller
{
    public function index(Request $request): View
    {
        $claims = $this->filteredClaims($request)
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

    public function export(Request $request): StreamedResponse
    {
        $claims = $this->filteredClaims($request)
            ->latest('claim_date')
            ->get();

        $filename = 'claims-report-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($claims) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Submitter',
                'Email',
                'Claim Date',
                'Category',
                'Amount',
                'Status',
                'Comment',
                'Reviewed By',
                'Reviewed At',
            ]);

            foreach ($claims as $claim) {
                fputcsv($handle, [
                    $claim->submitter->name,
                    $claim->submitter->email,
                    $claim->claim_date->format('Y-m-d'),
                    $claim->category->name,
                    number_format($claim->amount, 2, '.', ''),
                    ucfirst($claim->status),
                    $claim->manager_comment ?? '',
                    $claim->reviewer?->name ?? '',
                    $claim->reviewed_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
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

        if (in_array($status, [ExpenseClaim::STATUS_APPROVED, ExpenseClaim::STATUS_REJECTED], true)) {
            $claim->load(['submitter', 'category', 'reviewer']);
            Mail::to($claim->submitter->email)->send(new ClaimReviewedMail($claim));
        }

        return redirect()->route('admin.claims.index')->with('success', 'Claim status updated.');
    }

    private function filteredClaims(Request $request)
    {
        return ExpenseClaim::query()
            ->with(['submitter', 'category', 'reviewer'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->user_id))
            ->when($request->filled('month'), function ($query) use ($request) {
                $month = Carbon::createFromFormat('Y-m', $request->month);

                $query->whereBetween('claim_date', [
                    $month->copy()->startOfMonth()->toDateString(),
                    $month->copy()->endOfMonth()->toDateString(),
                ]);
            })
            ->when($request->filled('date_from'), fn ($query) => $query->whereDate('claim_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($query) => $query->whereDate('claim_date', '<=', $request->date_to));
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
