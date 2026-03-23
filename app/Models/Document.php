<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    public const TYPE_LABELS = [
        'nrc' => 'NRC Copy',
        'payslip' => 'Payslip',
        'bank_statement' => 'Bank Statement',
        'employment_letter' => 'Employment Letter',
        'collateral_proof' => 'Collateral Proof',
        'selfie' => 'Selfie with NRC',
        'other' => 'Additional Document',
    ];

    public const STATUS_LABELS = [
        'pending' => 'Pending Review',
        'approved' => 'Approved',
        'resubmission_requested' => 'Resubmission Requested',
        'replaced' => 'Replaced',
    ];

    protected $fillable = [
        'loan_application_id', 'type', 'original_filename',
        'file_path', 'file_size', 'status', 'is_verified', 'verified_by',
        'reviewed_by', 'reviewed_at', 'review_notes', 'notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function typeLabels(): array
    {
        return self::TYPE_LABELS;
    }

    public static function allowedTypes(): array
    {
        return array_keys(self::TYPE_LABELS);
    }

    public function label(): string
    {
        return self::TYPE_LABELS[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function statusClasses(): string
    {
        return match ($this->status) {
            'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'resubmission_requested' => 'bg-orange-50 text-orange-600 border-orange-100',
            'replaced' => 'bg-slate-100 text-slate-500 border-slate-200',
            default => 'bg-gray-50 text-gray-500 border-gray-100',
        };
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function needsResubmission(): bool
    {
        return $this->status === 'resubmission_requested';
    }

    public function isActive(): bool
    {
        return $this->status !== 'replaced';
    }

    public function canPreviewInline(): bool
    {
        return in_array(strtolower(pathinfo($this->original_filename, PATHINFO_EXTENSION)), [
            'pdf',
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp',
        ], true);
    }
}
