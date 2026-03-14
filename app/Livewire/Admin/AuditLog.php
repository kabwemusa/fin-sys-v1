<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class AuditLog extends Component
{
    use WithPagination;

    public string $search    = '';
    public string $dateFrom  = '';
    public string $dateTo    = '';

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->toDateString();
        $this->dateTo   = now()->toDateString();
    }

    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $activities = Activity::with('causer')
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->search,   fn($q) => $q->where('description', 'ilike', "%{$this->search}%"))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.audit-log', compact('activities'))
            ->layout('components.layouts.admin', ['title' => 'Audit Log']);
    }
}
