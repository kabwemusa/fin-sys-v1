<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'nrc_number', 'date_of_birth', 'gender', 'marital_status',
        'residential_address', 'city', 'province', 'employer_name',
        'employer_address', 'job_title', 'employment_date', 'monthly_income',
        'bank_name', 'bank_account_number', 'bank_branch',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'employment_date' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }
}
