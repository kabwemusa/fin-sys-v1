@php
$statusConfig = [
    'pending'        => ['bg'=>'bg-amber-50',   'text'=>'text-amber-600',  'dot'=>'bg-amber-400',  'label'=>'Pending'],
    'under_review'   => ['bg'=>'bg-blue-50',    'text'=>'text-blue-600',   'dot'=>'bg-blue-400',   'label'=>'Under Review'],
    'approved'       => ['bg'=>'bg-emerald-50', 'text'=>'text-emerald-600','dot'=>'bg-emerald-400','label'=>'Approved'],
    'rejected'       => ['bg'=>'bg-red-50',     'text'=>'text-red-500',    'dot'=>'bg-red-400',    'label'=>'Rejected'],
    'disbursed'      => ['bg'=>'bg-green-50',   'text'=>'text-green-600',  'dot'=>'bg-green-400',  'label'=>'Disbursed'],
    'info_requested' => ['bg'=>'bg-orange-50',  'text'=>'text-orange-600', 'dot'=>'bg-orange-400', 'label'=>'Info Requested'],
];
@endphp

<div class="p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    {{-- Metrics row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="portal-panel rounded-2xl p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Pending Review</p>
            <p class="text-3xl font-semibold text-amber-500">{{ $totalPending }}</p>
            <p class="text-xs text-gray-400 mt-1">applications awaiting action</p>
        </div>
        <div class="portal-panel rounded-2xl p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Disbursed This Month</p>
            <p class="text-2xl font-semibold text-[#166534]">ZMW {{ number_format($totalDisbursed, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">total loan funds out</p>
        </div>
        <div class="portal-panel rounded-2xl p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Overdue Loans</p>
            <p class="text-3xl font-semibold {{ $overdue > 0 ? 'text-red-500' : 'text-emerald-500' }}">{{ $overdue }}</p>
            <p class="text-xs text-gray-400 mt-1">past due date</p>
        </div>
        <div class="portal-panel rounded-2xl p-5">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Approval Rate</p>
            <p class="text-3xl font-semibold text-emerald-500">{{ $approvalRate }}%</p>
            <p class="text-xs text-gray-400 mt-1">this month</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Monthly applications bar chart --}}
        <div class="portal-panel lg:col-span-2 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-gray-600 mb-5">Applications — Last 6 Months</h3>
            @if($monthlyStats->count())
                @php $maxTotal = max(1, $monthlyStats->max('total')); @endphp
                <div class="flex items-end gap-4 h-36">
                    @foreach($monthlyStats as $stat)
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full flex flex-col justify-end h-28 gap-0.5">
                                <div class="w-full rounded-t-md bg-linear-to-t from-[#166534] to-[#4EA8D9] transition-all duration-500"
                                     style="height: {{ max(4, round($stat->total / $maxTotal * 100)) }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $stat->month }}</span>
                            <span class="text-xs font-medium text-gray-600">{{ $stat->total }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-400 text-center py-10">No data yet</p>
            @endif
        </div>

        {{-- Quick stats --}}
        <div class="portal-panel rounded-2xl p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-600 mb-1">Quick Links</h3>
            <a href="{{ route('admin.applications') }}?status=pending" class="portal-action flex items-center justify-between rounded-xl bg-amber-50 p-3 transition-colors hover:bg-amber-100 group">
                <span class="text-sm text-amber-700 font-medium">Pending Applications</span>
                <span class="text-sm font-bold text-amber-600">{{ $totalPending }}</span>
            </a>
            <a href="{{ route('admin.repayments') }}" class="portal-action flex items-center justify-between rounded-xl bg-[#4EA8D9]/10 p-3 transition-colors hover:bg-[#4EA8D9]/15 group">
                <span class="text-sm font-medium text-[#1B4F72]">Record Repayment</span>
                <svg class="w-4 h-4 text-[#4EA8D9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.products') }}" class="portal-action flex items-center justify-between rounded-xl bg-gray-50 p-3 transition-colors hover:bg-gray-100 group">
                <span class="text-sm text-gray-700 font-medium">Manage Products</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('admin.reports') }}" class="portal-action flex items-center justify-between rounded-xl bg-gray-50 p-3 transition-colors hover:bg-gray-100 group">
                <span class="text-sm text-gray-700 font-medium">View Reports</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- Recent pending applications --}}
    <div class="portal-panel rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Recent Pending Applications</h3>
            <a href="{{ route('admin.applications') }}" class="portal-action text-xs text-[#166534] hover:underline">View all →</a>
        </div>
        @if($recentApplications->isEmpty())
            <p class="text-sm text-gray-400 text-center py-8">No pending applications</p>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentApplications as $app)
                    @php $sc = $statusConfig[$app->status] ?? $statusConfig['pending']; @endphp
                    <a href="{{ route('admin.application.review', $app->id) }}"
                       class="portal-card-hover flex items-center justify-between px-6 py-3.5 transition-colors hover:bg-gray-50 group">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="text-sm font-medium text-[#166534] group-hover:underline">{{ $app->reference }}</p>
                                <p class="text-xs text-gray-400">{{ $app->customer->user->name ?? '—' }} &middot; {{ $app->loanProduct->name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-gray-700">ZMW {{ number_format($app->amount_requested, 0) }}</span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }} animate-pulse"></span>
                                {{ $sc['label'] }}
                            </span>
                            <p class="text-xs text-gray-400 hidden md:block">{{ $app->created_at->format('d M') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
