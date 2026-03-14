<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collateral extends Model
{
    protected $fillable = [
        'loan_application_id', 'type', 'description',
        'estimated_value', 'registration_number', 'is_verified',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }
}
