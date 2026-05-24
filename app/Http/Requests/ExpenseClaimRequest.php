<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'gt:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'claim_date' => ['required', 'date', 'before_or_equal:today'],
            'description' => ['required', 'string', 'min:10'],
            'receipt_note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.regex' => 'The amount must be a positive number with no more than 2 decimal places.',
            'claim_date.before_or_equal' => 'The claim date cannot be in the future.',
            'description.min' => 'The description must be at least 10 characters.',
        ];
    }
}
