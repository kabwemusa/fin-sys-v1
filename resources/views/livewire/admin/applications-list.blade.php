@php
$statusConfig = [
    'pending'        => ['bg'=>'bg-[#FEF9E1]',   'text'=>'text-amber-600',  'dot'=>'bg-amber-400',  'label'=>'Pending'],
    'under_review'   => ['bg'=>'bg-blue-50',    'text'=>'text-blue-600',   'dot'=>'bg-blue-400',   'label'=>'Under Review'],
    'approved'       => ['bg'=>'bg-emerald-50', 'text'=>'text-emerald-600','dot'=>'bg-emerald-400','label'=>'Approved'],
    'rejected'       => ['bg'=>'bg-red-50',     'text'=>'text-red-500',    'dot'=>'bg-red-400',    'label'=>'Rejected'],
    'disbursed'      => ['bg'=>'bg-[#FEF9E1]',   'text'=>'text-[#E98C00]',  'dot'=>'bg-green-400',  'label'=>'Disbursed'],
    'info_requested' => ['bg'=>'bg-[#FEF9E1]',  'text'=>'text-[#E98C00]', 'dot'=>'bg-[#E98C00]', 'label'=>'Info Requested'],
    'closed'         => ['bg'=>'bg-[#FEF9E1]',   'text'=>'text-gray-500',   'dot'=>'bg-gray-400',   'label'=>'Closed'],
    'cancelled'      => ['bg'=>'bg-[#FEF9E1]',   'text'=>'text-gray-400',   'dot'=>'bg-gray-300',   'label'=>'Cancelled'],
];
@endphp

<div class="p-6 lg:p-8 max-w-7xl mx-auto space-y-5">

    {{-- Header + filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by reference or customer…"
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800
                       focus:outline-none focus:ring-2 focus:ring-[#E98C00]/20 focus:border-[#E98C00] transition-all">
        </div>
        <select wire:model.live="status"
            class="px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:border-[#E98C00]">
            <option value="">All statuses</option>
            <option value="pending">Pending</option>
            <option value="under_review">Under Review</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="info_requested">Info Requested</option>
            <option value="disbursed">Disbursed</option>
        </select>
        <select wire:model.live="productId"
            class="px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:border-[#E98C00]">
            <option value="">All products</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="portal-panel rounded-2xl overflow-hidden">
        <div class="hidden md:grid grid-cols-12 gap-3 px-6 py-3 bg-[#FEF9E1] border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
            <button class="portal-action col-span-3 text-left flex items-center gap-1" wire:click="sort('reference')">
                Reference
                @if($sortBy === 'reference') <span class="{{ $sortDir === 'asc' ? 'rotate-180' : '' }} transition-transform">↓</span> @endif
            </button>
            <div class="col-span-3">Customer</div>
            <div class="col-span-2">Product</div>
            <button class="portal-action col-span-2 text-right flex items-center justify-end gap-1" wire:click="sort('amount_requested')">
                Amount
                @if($sortBy === 'amount_requested') <span class="{{ $sortDir === 'asc' ? 'rotate-180' : '' }} transition-transform">↓</span> @endif
            </button>
            <div class="col-span-2 text-center">Status</div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($applications as $app)
                @php $sc = $statusConfig[$app->status] ?? $statusConfig['pending']; @endphp
                <a href="{{ route('admin.application.review', $app->id) }}"
                   class="portal-card-hover grid grid-cols-12 gap-3 px-6 py-4 hover:bg-[#FEF9E1]/60 transition-colors items-center group">
                    <div class="col-span-12 md:col-span-3">
                        <p class="text-sm font-medium text-[#E98C00] group-hover:underline">{{ $app->reference }}</p>
                        <p class="text-xs text-gray-400">{{ $app->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="hidden md:block col-span-3">
                        <p class="text-sm text-gray-700">{{ $app->customer->user->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $app->customer->user->phone ?? '' }}</p>
                    </div>
                    <div class="hidden md:block col-span-2">
                        <p class="text-xs text-gray-500">{{ $app->loanProduct->name }}</p>
                    </div>
                    <div class="col-span-6 md:col-span-2 text-right">
                        <p class="text-sm font-medium text-gray-800">ZMW {{ number_format($app->amount_requested, 0) }}</p>
                        <p class="text-xs text-gray-400">{{ $app->tenure_months }} mo</p>
                    </div>
                    <div class="col-span-6 md:col-span-2 flex md:justify-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} {{ in_array($app->status, ['pending','under_review']) ? 'animate-pulse' : '' }}"></span>
                            {{ $sc['label'] }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400">No applications found</p>
                </div>
            @endforelse
        </div>

        @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</div>
