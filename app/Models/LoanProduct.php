<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

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

    public function documentChecklist(): array
    {
        $items = [
            [
                'type' => 'nrc',
                'label' => Arr::get(Document::TYPE_LABELS, 'nrc'),
                'required' => true,
                'hint' => 'Front and back of your National Registration Card.',
            ],
            [
                'type' => 'payslip',
                'label' => Arr::get(Document::TYPE_LABELS, 'payslip'),
                'required' => false,
                'hint' => 'Latest salary slip or proof of regular income.',
            ],
            [
                'type' => 'bank_statement',
                'label' => Arr::get(Document::TYPE_LABELS, 'bank_statement'),
                'required' => false,
                'hint' => 'Recent bank statement showing salary or business inflows.',
            ],
            [
                'type' => 'employment_letter',
                'label' => Arr::get(Document::TYPE_LABELS, 'employment_letter'),
                'required' => false,
                'hint' => 'Employment confirmation or recent introduction letter.',
            ],
            [
                'type' => 'selfie',
                'label' => Arr::get(Document::TYPE_LABELS, 'selfie'),
                'required' => false,
                'hint' => 'Clear selfie holding your NRC for identity verification.',
            ],
        ];

        if ($this->requires_collateral) {
            array_splice($items, 4, 0, [[
                'type' => 'collateral_proof',
                'label' => Arr::get(Document::TYPE_LABELS, 'collateral_proof'),
                'required' => true,
                'hint' => 'Title deed, log book, or another proof of ownership for the pledged asset.',
            ]]);
        }

        return $items;
    }

    public function requiredDocumentTypes(): array
    {
        return collect($this->documentChecklist())
            ->filter(fn (array $item) => $item['required'])
            ->pluck('type')
            ->values()
            ->all();
    }
}
