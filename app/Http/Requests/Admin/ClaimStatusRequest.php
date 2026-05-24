<?php

namespace App\Http\Requests\Admin;

use App\Models\ExpenseClaim;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClaimStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in([
                ExpenseClaim::STATUS_PENDING,
                ExpenseClaim::STATUS_APPROVED,
                ExpenseClaim::STATUS_REJECTED,
            ])],
            'manager_comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
