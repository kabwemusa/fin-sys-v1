<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $fillable = [
        'name', 'slug', 'type', 'description', 'min_amount', 'max_amount',
        'min_tenure_months', 'max_tenure_months', 'interest_rate',
        'requires_collateral', 'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'requires_collateral' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
