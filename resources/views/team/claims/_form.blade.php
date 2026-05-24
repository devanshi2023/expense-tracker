@csrf

<label>
    Category
    <select name="category_id" required>
        <option value="">Select category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $claim->category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</label>

<label>
    Amount
    <input type="number" name="amount" min="0.01" step="0.01" value="{{ old('amount', $claim->amount) }}" required>
</label>

<label>
    Claim Date
    <input type="date" name="claim_date" value="{{ old('claim_date', optional($claim->claim_date)->format('Y-m-d') ?? $claim->claim_date) }}" max="{{ now()->toDateString() }}" required>
</label>

<label>
    Description
    <textarea name="description" required>{{ old('description', $claim->description) }}</textarea>
</label>

<label>
    Receipt Note
    <textarea name="receipt_note">{{ old('receipt_note', $claim->receipt_note) }}</textarea>
</label>

<button type="submit">{{ $buttonText }}</button>
