<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        'loan_application_id', 'amount', 'payment_date',
        'payment_method', 'reference_number', 'recorded_by', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function recordedByUser()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
