@php
$statusConfig = ['pending'=>['bg'=>'bg-amber-50','text'=>'text-amber-600','label'=>'Pending'],'under_review'=>['bg'=>'bg-blue-50','text'=>'text-blue-600','label'=>'Under Review'],'approved'=>['bg'=>'bg-emerald-50','text'=>'text-emerald-600','label'=>'Approved'],'rejected'=>['bg'=>'bg-red-50','text'=>'text-red-500','label'=>'Rejected'],'disbursed'=>['bg'=>'bg-green-50','text'=>'text-green-600','label'=>'Disbursed'],'info_requested'=>['bg'=>'bg-orange-50','text'=>'text-orange-600','label'=>'Info Requested']];
@endphp

<div class="p-6 lg:p-8 max-w-5xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.customers') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition-colors mb-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Customers
        </a>
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#1B4F72]/10 flex items-center justify-center">
                    <span class="text-[#1B4F72] text-xl font-semibold">{{ strtoupper(substr($customer->user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $customer->user->name }}</h2>
                    <p class="text-sm text-gray-400">{{ $customer->user->email }} &middot; {{ $customer->user->phone ?? '—' }}</p>
                </div>
            </div>
            <button wire:click="resetPassword" wire:confirm="Reset this customer's password?"
                class="px-4 py-2 text-sm font-medium text-orange-600 bg-orange-50 border border-orange-100 rounded-xl hover:bg-orange-100 transition-colors">
                Reset Password
            </button>
        </div>
    </div>

    {{-- Summary stats --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Borrowed</p>
            <p class="text-xl font-semibold text-[#1B4F72]">ZMW {{ number_format($totalBorrowed, 0) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Repaid</p>
            <p class="text-xl font-semibold text-emerald-600">ZMW {{ number_format($totalRepaid, 0) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Outstanding</p>
            <p class="text-xl font-semibold {{ $totalOutstanding > 0 ? 'text-red-500' : 'text-gray-400' }}">ZMW {{ number_format($totalOutstanding, 0) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Profile --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Profile</h3>
            </div>
            <div class="px-5 py-4 grid grid-cols-2 gap-y-3 text-sm">
                <span class="text-gray-400">NRC</span><span class="font-medium text-gray-700">{{ $customer->nrc_number }}</span>
                <span class="text-gray-400">Date of Birth</span><span class="font-medium text-gray-700">{{ $customer->date_of_birth?->format('d M Y') ?? '—' }}</span>
                <span class="text-gray-400">Gender</span><span class="font-medium text-gray-700">{{ ucfirst($customer->gender ?? '—') }}</span>
                <span class="text-gray-400">City</span><span class="font-medium text-gray-700">{{ $customer->city }}, {{ $customer->province }}</span>
                <span class="text-gray-400">Employer</span><span class="font-medium text-gray-700">{{ $customer->employer_name ?? '—' }}</span>
                <span class="text-gray-400">Income</span><span class="font-semibold text-[#1B4F72]">ZMW {{ number_format($customer->monthly_income ?? 0, 0) }}/mo</span>
                <span class="text-gray-400">Bank</span><span class="font-medium text-gray-700">{{ $customer->bank_name ?? '—' }}</span>
                <span class="text-gray-400">Account</span><span class="font-medium text-gray-700">{{ $customer->bank_account_number ?? '—' }}</span>
            </div>
        </div>

        {{-- Applications --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Loan Applications</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($customer->loanApplications as $app)
                    @php $sc = $statusConfig[$app->status] ?? ['bg'=>'bg-gray-100','text'=>'text-gray-500','label'=>ucfirst($app->status)]; @endphp
                    <a href="{{ route('admin.application.review', $app->id) }}"
                       class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors group">
                        <div>
                            <p class="text-sm font-medium text-[#1B4F72] group-hover:underline">{{ $app->reference }}</p>
                            <p class="text-xs text-gray-400">{{ $app->loanProduct->name }} &middot; {{ $app->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-700">ZMW {{ number_format($app->amount_requested, 0) }}</p>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">{{ $sc['label'] }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-gray-400 px-5 py-6 text-center">No applications</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
