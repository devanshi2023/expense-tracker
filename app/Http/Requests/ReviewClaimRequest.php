<?php

namespace App\Http\Requests;

use App\Models\ExpenseClaim;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([ExpenseClaim::STATUS_APPROVED, ExpenseClaim::STATUS_REJECTED])],
            'manager_comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
