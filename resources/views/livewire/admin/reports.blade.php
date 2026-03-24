<div class="p-6 lg:p-8 max-w-6xl mx-auto space-y-5">

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <select wire:model.live="reportType"
            class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:border-[#E98C00]">
            <option value="disbursement">Disbursement Report</option>
            <option value="repayment">Repayment Report</option>
            <option value="overdue">Overdue Report</option>
            <option value="summary">Summary by Status</option>
        </select>
        @if($reportType !== 'overdue')
        <input wire:model.live="dateFrom" type="date"
            class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
        <input wire:model.live="dateTo" type="date"
            class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
        @endif
    </div>

    {{-- Summary card --}}
    @if(isset($summary['count']))
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Records</p>
            <p class="text-3xl font-semibold text-[#E98C00]">{{ $summary['count'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Amount (ZMW)</p>
            <p class="text-3xl font-semibold text-emerald-600">{{ number_format($summary['total'] ?? 0, 2) }}</p>
        </div>
    </div>
    @endif

    {{-- Data table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        @if($reportType === 'disbursement')
            <div class="divide-y divide-gray-50">
                @forelse($data as $app)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <a href="{{ route('admin.application.review', $app->id) }}" class="text-sm font-medium text-[#E98C00] hover:underline">{{ $app->reference }}</a>
                            <p class="text-xs text-gray-400">{{ $app->customer->user->name ?? '—' }} &middot; {{ $app->loanProduct->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">ZMW {{ number_format($app->amount_approved, 2) }}</p>
                            <p class="text-xs text-gray-400">Disbursed {{ $app->disbursed_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-12">No disbursements in this period</p>
                @endforelse
            </div>

        @elseif($reportType === 'repayment')
            <div class="divide-y divide-gray-50">
                @forelse($data as $r)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-[#E98C00]">{{ $r->loanApplication->reference }}</p>
                            <p class="text-xs text-gray-400">{{ $r->loanApplication->customer->user->name ?? '—' }} &middot; {{ $r->payment_date->format('d M Y') }}</p>
                        </div>
                        <p class="text-sm font-semibold text-emerald-600">ZMW {{ number_format($r->amount, 2) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-12">No repayments in this period</p>
                @endforelse
            </div>

        @elseif($reportType === 'overdue')
            <div class="divide-y divide-gray-50">
                @forelse($data as $app)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <a href="{{ route('admin.application.review', $app->id) }}" class="text-sm font-medium text-[#E98C00] hover:underline">{{ $app->reference }}</a>
                            <p class="text-xs text-gray-400">{{ $app->customer->user->name ?? '—' }} &middot; Due {{ $app->due_date->format('d M Y') }}</p>
                        </div>
                        <p class="text-sm font-semibold text-red-500">ZMW {{ number_format($app->outstandingBalance(), 2) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-12">No overdue loans</p>
                @endforelse
            </div>

        @elseif($reportType === 'summary')
            <div class="divide-y divide-gray-50">
                @forelse($data as $row)
                    <div class="flex items-center justify-between px-6 py-4">
                        <p class="text-sm font-medium text-gray-700 capitalize">{{ ucfirst(str_replace('_',' ',$row->status)) }}</p>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">{{ $row->count }} apps</p>
                            <p class="text-xs text-gray-400">ZMW {{ number_format($row->total_requested, 0) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-12">No data</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
