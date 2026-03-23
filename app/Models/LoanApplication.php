<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class LoanApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'customer_id', 'loan_product_id', 'amount_requested',
        'tenure_months', 'purpose', 'status', 'interest_rate',
        'amount_approved', 'monthly_repayment', 'admin_notes',
        'rejection_reason', 'info_requested_note', 'reviewed_by',
        'reviewed_at', 'disbursed_at', 'due_date',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'monthly_repayment' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($application) {
            if (empty($application->reference)) {
                $year = now()->year;
                $lastApp = static::withTrashed()
                    ->whereYear('created_at', $year)
                    ->orderByDesc('id')
                    ->first();

                $nextNumber = $lastApp
                    ? (int) substr($lastApp->reference, -5) + 1
                    : 1;

                $application->reference = sprintf('LN-%d-%05d', $year, $nextNumber);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function loanProduct()
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function collaterals()
    {
        return $this->hasMany(Collateral::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function totalRepaid(): float
    {
        return $this->repayments()->sum('amount');
    }

    public function outstandingBalance(): float
    {
        if (!$this->amount_approved) return 0;

        $totalDue = $this->monthly_repayment * $this->tenure_months;
        return max(0, $totalDue - $this->totalRepaid());
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isDisbursed(): bool { return $this->status === 'disbursed'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function activeDocuments(): Collection
    {
        $documents = $this->relationLoaded('documents')
            ? $this->documents
            : $this->documents()->get();

        return $documents
            ->filter(fn (Document $document) => $document->isActive())
            ->sortByDesc('created_at')
            ->values();
    }

    public function latestDocumentForType(string $type): ?Document
    {
        return $this->activeDocuments()
            ->first(fn (Document $document) => $document->type === $type);
    }

    public function documentChecklist(): array
    {
        $product = $this->relationLoaded('loanProduct')
            ? $this->loanProduct
            : $this->loanProduct()->first();

        if (! $product) {
            return [];
        }

        return collect($product->documentChecklist())
            ->map(function (array $item) {
                $document = $this->latestDocumentForType($item['type']);

                return array_merge($item, [
                    'document' => $document,
                    'submitted' => (bool) $document,
                    'approved' => $document?->isApproved() ?? false,
                    'needs_resubmission' => $document?->needsResubmission() ?? false,
                ]);
            })
            ->all();
    }

    public function outstandingDocumentRequests(): Collection
    {
        return $this->activeDocuments()
            ->filter(fn (Document $document) => $document->needsResubmission())
            ->values();
    }
}
