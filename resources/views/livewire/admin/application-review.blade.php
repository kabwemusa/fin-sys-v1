@php
$statusConfig = [
    'pending'        => ['color'=>'text-amber-500',  'bg'=>'bg-amber-50',   'border'=>'border-amber-100', 'label'=>'Pending'],
    'under_review'   => ['color'=>'text-blue-500',   'bg'=>'bg-blue-50',    'border'=>'border-blue-100',  'label'=>'Under Review'],
    'approved'       => ['color'=>'text-emerald-500','bg'=>'bg-emerald-50', 'border'=>'border-emerald-100','label'=>'Approved'],
    'rejected'       => ['color'=>'text-red-500',    'bg'=>'bg-red-50',     'border'=>'border-red-100',   'label'=>'Rejected'],
    'disbursed'      => ['color'=>'text-green-600',  'bg'=>'bg-green-50',   'border'=>'border-green-100', 'label'=>'Disbursed'],
    'info_requested' => ['color'=>'text-orange-500', 'bg'=>'bg-orange-50',  'border'=>'border-orange-100','label'=>'Info Requested'],
];
$sc = $statusConfig[$application->status] ?? $statusConfig['pending'];
$docLabels = ['nrc'=>'NRC','payslip'=>'Payslip','bank_statement'=>'Bank Statement','employment_letter'=>'Employment Letter','collateral_proof'=>'Collateral Proof','selfie'=>'Selfie','additional'=>'Additional'];
@endphp

<div x-data="{ modal: '' }" class="p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    {{-- Page header --}}
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <a href="{{ route('admin.applications') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition-colors mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Applications
            </a>
            <h2 class="text-xl font-semibold text-gray-800">{{ $application->reference }}</h2>
            <p class="text-sm text-gray-400 font-light mt-0.5">{{ $application->loanProduct->name }} &middot; Applied {{ $application->created_at->format('d M Y') }}</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium {{ $sc['bg'] }} {{ $sc['color'] }} {{ $sc['border'] }} border">
                <span class="w-2 h-2 rounded-full {{ str_replace('text-','bg-',$sc['color']) }}"></span>
                {{ $sc['label'] }}
            </span>
            @if($application->status === 'pending')
                <button wire:click="markUnderReview"
                    class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 transition-colors">
                    Mark Under Review
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Customer info --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Personal --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal Details</h3>
                </div>
                <div class="px-5 py-4 grid grid-cols-2 gap-y-3 text-sm">
                    @php $u = $application->customer->user; $c = $application->customer; @endphp
                    <span class="text-gray-400">Name</span><span class="font-medium text-gray-700">{{ $u->name }}</span>
                    <span class="text-gray-400">Email</span><span class="font-medium text-gray-700">{{ $u->email }}</span>
                    <span class="text-gray-400">Phone</span><span class="font-medium text-gray-700">{{ $u->phone }}</span>
                    <span class="text-gray-400">NRC</span><span class="font-medium text-gray-700">{{ $c->nrc_number }}</span>
                    <span class="text-gray-400">Date of Birth</span><span class="font-medium text-gray-700">{{ $c->date_of_birth?->format('d M Y') }}</span>
                    <span class="text-gray-400">Gender</span><span class="font-medium text-gray-700">{{ ucfirst($c->gender ?? '—') }}</span>
                    <span class="text-gray-400">Marital Status</span><span class="font-medium text-gray-700">{{ ucfirst($c->marital_status ?? '—') }}</span>
                    <span class="text-gray-400">City</span><span class="font-medium text-gray-700">{{ $c->city }}, {{ $c->province }}</span>
                    <span class="text-gray-400 col-span-2 border-t border-gray-50 pt-2">Address</span>
                    <span class="font-medium text-gray-700 col-span-2">{{ $c->residential_address }}</span>
                </div>
            </div>

            {{-- Employment --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Employment</h3>
                </div>
                <div class="px-5 py-4 grid grid-cols-2 gap-y-3 text-sm">
                    <span class="text-gray-400">Employer</span><span class="font-medium text-gray-700">{{ $c->employer_name ?? '—' }}</span>
                    <span class="text-gray-400">Job Title</span><span class="font-medium text-gray-700">{{ $c->job_title ?? '—' }}</span>
                    <span class="text-gray-400">Monthly Income</span><span class="font-semibold text-[#1B4F72]">ZMW {{ number_format($c->monthly_income ?? 0, 0) }}</span>
                    <span class="text-gray-400">Since</span><span class="font-medium text-gray-700">{{ $c->employment_date?->format('d M Y') ?? '—' }}</span>
                    <span class="text-gray-400">Bank</span><span class="font-medium text-gray-700">{{ $c->bank_name ?? '—' }} — {{ $c->bank_account_number ?? '—' }}</span>
                </div>
            </div>

            {{-- Collateral --}}
            @if($application->collaterals->count())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Collateral</h3>
                    </div>
                    @foreach($application->collaterals as $col)
                        <div class="px-5 py-4 grid grid-cols-2 gap-y-3 text-sm">
                            <span class="text-gray-400">Type</span><span class="font-medium text-gray-700">{{ ucfirst($col->type) }}</span>
                            <span class="text-gray-400">Est. Value</span><span class="font-semibold text-[#1B4F72]">ZMW {{ number_format($col->estimated_value, 0) }}</span>
                            <span class="text-gray-400">Reg. No.</span><span class="font-medium text-gray-700">{{ $col->registration_number ?? '—' }}</span>
                            <span class="text-gray-400 col-span-2 border-t border-gray-50 pt-2">Description</span>
                            <span class="font-medium text-gray-700 col-span-2">{{ $col->description }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Documents --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Documents</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($application->documents as $doc)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $docLabels[$doc->type] ?? ucfirst(str_replace('_',' ',$doc->type)) }}</p>
                                <p class="text-xs text-gray-400">{{ $doc->original_filename }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($doc->is_verified)
                                    <span class="text-xs text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full font-medium">Verified</span>
                                @else
                                    <button wire:click="verifyDocument({{ $doc->id }})"
                                        class="text-xs text-[#1B4F72] bg-blue-50 px-2.5 py-1 rounded-full font-medium hover:bg-blue-100 transition-colors">
                                        Mark Verified
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 px-5 py-4">No documents uploaded</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT: Loan info + actions --}}
        <div class="space-y-4">

            {{-- Loan details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Loan Details</h3>
                </div>
                <div class="px-5 py-4 space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-400">Requested</span><span class="font-semibold text-[#1B4F72]">ZMW {{ number_format($application->amount_requested, 0) }}</span></div>
                    @if($application->amount_approved)
                    <div class="flex justify-between"><span class="text-gray-400">Approved</span><span class="font-semibold text-emerald-600">ZMW {{ number_format($application->amount_approved, 0) }}</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-gray-400">Tenure</span><span class="font-medium text-gray-700">{{ $application->tenure_months }} months</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Rate</span><span class="font-medium text-gray-700">{{ $application->interest_rate }}%/mo</span></div>
                    @if($application->monthly_repayment)
                    <div class="flex justify-between border-t border-gray-50 pt-3"><span class="text-gray-500 font-medium">Monthly Payment</span><span class="font-semibold text-gray-800">ZMW {{ number_format($application->monthly_repayment, 2) }}</span></div>
                    @endif
                    @if($application->purpose)
                    <div class="border-t border-gray-50 pt-3"><p class="text-gray-400 text-xs mb-1">Purpose</p><p class="text-gray-700">{{ $application->purpose }}</p></div>
                    @endif
                </div>
            </div>

            {{-- Action panel --}}
            @if(!in_array($application->status, ['disbursed', 'closed', 'cancelled']))
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Actions</h3>

                    @if(in_array($application->status, ['pending','under_review','info_requested']))
                        <button x-on:click="modal = 'approve'"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Approve
                        </button>
                        <button x-on:click="modal = 'info'"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-orange-50 text-orange-600 text-sm font-medium rounded-xl border border-orange-100 hover:bg-orange-100 transition-colors">
                            Request More Info
                        </button>
                        <button x-on:click="modal = 'reject'"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-red-50 text-red-600 text-sm font-medium rounded-xl border border-red-100 hover:bg-red-100 transition-colors">
                            Reject
                        </button>
                    @endif

                    @if($application->status === 'approved')
                        <button x-on:click="modal = 'disburse'"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1B4F72] text-white text-sm font-medium rounded-xl hover:bg-[#154060] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mark as Disbursed
                        </button>
                    @endif
                </div>
            @endif

            {{-- Repayments summary (if disbursed) --}}
            @if($application->isDisbursed())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Repayment Summary</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-400">Total Repaid</span><span class="font-medium text-emerald-600">ZMW {{ number_format($application->totalRepaid(), 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Outstanding</span><span class="font-semibold text-[#1B4F72]">ZMW {{ number_format($application->outstandingBalance(), 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-400">Due Date</span><span class="font-medium text-gray-700">{{ $application->due_date?->format('d M Y') }}</span></div>
                    </div>
                    <a href="{{ route('admin.repayments') }}" class="block mt-4 text-center text-xs text-[#1B4F72] hover:underline">Record repayment →</a>
                </div>
            @endif

            {{-- Notes --}}
            @if($application->admin_notes)
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Admin Notes</p>
                    <p class="text-sm text-gray-600">{{ $application->admin_notes }}</p>
                </div>
            @endif
            @if($application->rejection_reason)
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                    <p class="text-xs font-medium text-red-500 uppercase tracking-wider mb-1">Rejection Reason</p>
                    <p class="text-sm text-red-700">{{ $application->rejection_reason }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ───────────────── MODALS ───────────────── --}}

    {{-- Approve modal --}}
    <div x-show="modal === 'approve'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" x-on:click="modal = ''"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-7 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">Approve Application</h2>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Amount Approved (ZMW)</label>
                <input wire:model="approveAmount" type="number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                @error('approveAmount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Interest Rate (%/mo)</label>
                    <input wire:model="approveRate" type="number" step="0.01" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tenure (months)</label>
                    <input wire:model="approveTenure" type="number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Admin Notes (internal)</label>
                <textarea wire:model="approveNotes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-gray-500 border border-gray-200 hover:bg-gray-50 transition-colors">Cancel</button>
                <button wire:click="approve" x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">Confirm Approval</button>
            </div>
        </div>
    </div>

    {{-- Reject modal --}}
    <div x-show="modal === 'reject'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" x-on:click="modal = ''"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-7 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">Reject Application</h2>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Rejection Reason <span class="text-red-400">*</span> (visible to customer)</label>
                <textarea wire:model="rejectReason" rows="3" placeholder="Insufficient income for requested amount…" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 resize-none"></textarea>
                @error('rejectReason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Internal Notes</label>
                <textarea wire:model="rejectNotes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-gray-500 border border-gray-200 hover:bg-gray-50 transition-colors">Cancel</button>
                <button wire:click="reject" x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-red-600 text-white hover:bg-red-700 transition-colors">Confirm Rejection</button>
            </div>
        </div>
    </div>

    {{-- Info request modal --}}
    <div x-show="modal === 'info'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" x-on:click="modal = ''"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-7 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">Request More Information</h2>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Note to Customer <span class="text-red-400">*</span></label>
                <textarea wire:model="infoNote" rows="4" placeholder="Please upload a clearer copy of your NRC and a recent payslip…" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] resize-none"></textarea>
                @error('infoNote') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-gray-500 border border-gray-200 hover:bg-gray-50 transition-colors">Cancel</button>
                <button wire:click="requestInfo" x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-orange-500 text-white hover:bg-orange-600 transition-colors">Send Request</button>
            </div>
        </div>
    </div>

    {{-- Disburse modal --}}
    <div x-show="modal === 'disburse'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" x-on:click="modal = ''"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-7 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800">Mark as Disbursed</h2>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Disbursement Date</label>
                <input wire:model="disburseDate" type="date" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                @error('disburseDate') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Notes</label>
                <textarea wire:model="disburseNotes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium text-gray-500 border border-gray-200 hover:bg-gray-50 transition-colors">Cancel</button>
                <button wire:click="disburse" x-on:click="modal = ''" class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-[#1B4F72] text-white hover:bg-[#154060] transition-colors">Confirm Disbursement</button>
            </div>
        </div>
    </div>

</div>
