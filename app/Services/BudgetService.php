<?php

namespace App\Services;

use App\Models\Category;
use App\Models\ExpenseClaim;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class BudgetService
{
    public function approvedSpendForCategoryMonth(int $categoryId, Carbon|string|null $month = null): string
    {
        $monthDate = $this->monthDate($month);

        return (string) ExpenseClaim::query()
            ->approvedInMonth($categoryId, $monthDate->format('Y-m'))
            ->sum('amount');
    }

    public function utilizationForMonth(Carbon|string|null $month = null): Collection
    {
        $monthDate = $this->monthDate($month);
        $start = $monthDate->copy()->startOfMonth()->toDateString();
        $end = $monthDate->copy()->endOfMonth()->toDateString();

        $spendByCategory = ExpenseClaim::query()
            ->where('status', ExpenseClaim::STATUS_APPROVED)
            ->whereBetween('claim_date', [$start, $end])
            ->selectRaw('category_id, sum(amount) as approved_spend')
            ->groupBy('category_id')
            ->pluck('approved_spend', 'category_id');

        return Category::query()
            ->orderBy('name')
            ->get()
            ->map(function (Category $category) use ($spendByCategory) {
                $spent = (float) ($spendByCategory[$category->id] ?? 0);
                $limit = (float) $category->monthly_budget_limit;

                return [
                    'category' => $category,
                    'spent' => number_format($spent, 2, '.', ''),
                    'limit' => number_format($limit, 2, '.', ''),
                    'percent' => $limit > 0 ? min(100, round(($spent / $limit) * 100)) : 0,
                    'is_over_limit' => $limit > 0 && $spent > $limit,
                ];
            });
    }

    public function approvalWarning(ExpenseClaim $claim): array
    {
        $claimMonth = $claim->claim_date instanceof Carbon
            ? $claim->claim_date
            : Carbon::parse($claim->claim_date);

        $currentSpend = (float) $this->approvedSpendForCategoryMonth($claim->category_id, $claimMonth);
        $limit = (float) $claim->category->monthly_budget_limit;
        $projectedSpend = $currentSpend + (float) $claim->amount;

        if ($limit <= 0 || $projectedSpend <= $limit) {
            return [
                'exceeds' => false,
                'message' => null,
            ];
        }

        return [
            'exceeds' => true,
            'message' => sprintf(
                'Approving this claim will take %s spend to %s / %s for %s.',
                $claim->category->name,
                number_format($projectedSpend, 2),
                number_format($limit, 2),
                $claimMonth->format('F Y')
            ),
        ];
    }

    private function monthDate(Carbon|string|null $month): Carbon
    {
        if ($month instanceof Carbon) {
            return $month->copy()->startOfMonth();
        }

        if (is_string($month) && $month !== '') {
            return Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        }

        return now()->startOfMonth();
    }
}
