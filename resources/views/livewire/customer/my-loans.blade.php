@php
$statusConfig = [
    'pending'        => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',  'dot' => 'bg-amber-400',  'label' => 'Pending'],
    'under_review'   => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',   'dot' => 'bg-blue-400',   'label' => 'Under Review'],
    'approved'       => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600','dot' => 'bg-emerald-400','label' => 'Approved'],
    'rejected'       => ['bg' => 'bg-red-50',     'text' => 'text-red-500',    'dot' => 'bg-red-400',    'label' => 'Rejected'],
    'disbursed'      => ['bg' => 'bg-green-50',   'text' => 'text-green-600',  'dot' => 'bg-green-400',  'label' => 'Disbursed'],
    'info_requested' => ['bg' => 'bg-orange-50',  'text' => 'text-orange-600', 'dot' => 'bg-orange-400', 'label' => 'Info Requested'],
    'closed'         => ['bg' => 'bg-gray-100',   'text' => 'text-gray-500',   'dot' => 'bg-gray-400',   'label' => 'Closed'],
    'cancelled'      => ['bg' => 'bg-gray-100',   'text' => 'text-gray-400',   'dot' => 'bg-gray-300',   'label' => 'Cancelled'],
];
@endphp

<div class="p-6 lg:p-8 max-w-5xl mx-auto">

    {{-- Page header --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">My Loan Applications</h2>
        <p class="text-sm text-gray-400 mt-0.5 font-light">Track the status of all your loan applications</p>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    @if($applications->isEmpty())
        {{-- Empty state --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium mb-1">No applications yet</p>
            <p class="text-sm text-gray-400 font-light mb-6">You haven't submitted any loan applications.</p>
            <a href="{{ route('home') }}#products"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1B4F72] text-white text-sm font-medium rounded-xl hover:bg-[#154060] transition-colors">
                Apply for a Loan
            </a>
        </div>
    @else
        {{-- Applications table --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
                <div class="col-span-3">Reference</div>
                <div class="col-span-3">Product</div>
                <div class="col-span-2 text-right">Amount</div>
                <div class="col-span-2 text-center">Status</div>
                <div class="col-span-1 text-center">Date</div>
                <div class="col-span-1"></div>
            </div>

            {{-- Rows --}}
            <div class="divide-y divide-gray-50">
                @foreach($applications as $app)
                    @php $sc = $statusConfig[$app->status] ?? $statusConfig['pending']; @endphp
                    <a href="{{ route('portal.loan.detail', $app->reference) }}"
                       class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50/60 transition-colors items-center group">
                        {{-- Reference --}}
                        <div class="col-span-12 md:col-span-3">
                            <p class="text-sm font-medium text-[#1B4F72] group-hover:underline tracking-wide">{{ $app->reference }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 md:hidden">{{ $app->loanProduct->name }}</p>
                        </div>
                        {{-- Product --}}
                        <div class="hidden md:block col-span-3">
                            <p class="text-sm text-gray-600">{{ $app->loanProduct->name }}</p>
                        </div>
                        {{-- Amount --}}
                        <div class="col-span-5 md:col-span-2 text-right">
                            <p class="text-sm font-medium text-gray-800">ZMW {{ number_format($app->amount_requested, 0) }}</p>
                            <p class="text-xs text-gray-400">{{ $app->tenure_months }} mo</p>
                        </div>
                        {{-- Status badge --}}
                        <div class="col-span-5 md:col-span-2 flex md:justify-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} inline-block {{ $app->status === 'pending' ? 'animate-pulse' : '' }}"></span>
                                {{ $sc['label'] }}
                            </span>
                        </div>
                        {{-- Date --}}
                        <div class="hidden md:block col-span-1 text-center">
                            <p class="text-xs text-gray-400">{{ $app->created_at->format('d M') }}</p>
                            <p class="text-xs text-gray-300">{{ $app->created_at->format('Y') }}</p>
                        </div>
                        {{-- Arrow --}}
                        <div class="col-span-2 md:col-span-1 flex justify-end">
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-[#1B4F72] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $applications->links() }}
                </div>
            @endif
        </div>

        {{-- Apply again CTA --}}
        <div class="mt-4 text-center">
            <a href="{{ route('home') }}#products" class="text-sm text-[#1B4F72] hover:underline font-medium">
                + Apply for another loan
            </a>
        </div>
    @endif
</div>
