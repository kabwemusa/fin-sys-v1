<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'loan_application_id', 'type', 'original_filename',
        'file_path', 'file_size', 'is_verified', 'verified_by', 'notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
