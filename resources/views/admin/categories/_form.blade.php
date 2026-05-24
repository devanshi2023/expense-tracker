@csrf

<label>
    Name
    <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
</label>

<label>
    Monthly Budget Limit
    <input type="number" name="monthly_budget_limit" min="0" step="0.01" value="{{ old('monthly_budget_limit', $category->monthly_budget_limit) }}" required>
</label>

<label class="inline">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
    Active
</label>

<button type="submit">{{ $buttonText }}</button>
