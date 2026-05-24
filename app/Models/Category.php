<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_budget_limit',
        'is_active',
    ];

    protected $casts = [
        'monthly_budget_limit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function expenseClaims(): HasMany
    {
        return $this->hasMany(ExpenseClaim::class);
    }
}
