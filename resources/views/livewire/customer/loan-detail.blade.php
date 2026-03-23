@php
$statusConfig = [
    'pending'        => ['color' => 'text-amber-500',  'bg' => 'bg-amber-50',   'border' => 'border-amber-100', 'label' => 'Pending'],
    'under_review'   => ['color' => 'text-blue-500',   'bg' => 'bg-blue-50',    'border' => 'border-blue-100',  'label' => 'Under Review'],
    'approved'       => ['color' => 'text-emerald-500','bg' => 'bg-emerald-50', 'border' => 'border-emerald-100','label' => 'Approved'],
    'rejected'       => ['color' => 'text-red-500',    'bg' => 'bg-red-50',     'border' => 'border-red-100',   'label' => 'Rejected'],
    'disbursed'      => ['color' => 'text-green-600',  'bg' => 'bg-green-50',   'border' => 'border-green-100', 'label' => 'Disbursed'],
    'info_requested' => ['color' => 'text-orange-500', 'bg' => 'bg-orange-50',  'border' => 'border-orange-100','label' => 'Info Requested'],
    'closed'         => ['color' => 'text-gray-500',   'bg' => 'bg-gray-100',   'border' => 'border-gray-200',  'label' => 'Closed'],
    'cancelled'      => ['color' => 'text-gray-400',   'bg' => 'bg-gray-50',    'border' => 'border-gray-100',  'label' => 'Cancelled'],
];
$sc = $statusConfig[$application->status] ?? $statusConfig['pending'];
$documents = $application->activeDocuments();
@endphp

<div class="p-6 lg:p-8 max-w-4xl mx-auto space-y-6">

    {{-- Back + Header --}}
    <div>
        <a href="{{ route('portal.loans') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition-colors mb-4">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Loans
        </a>
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $application->reference }}</h2>
                <p class="text-sm text-gray-400 mt-0.5 font-light">{{ $application->loanProduct->name }} &middot; Applied {{ $application->created_at->format('d M Y') }}</p>
            </div>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium {{ $sc['bg'] }} {{ $sc['color'] }} {{ $sc['border'] }} border">
                <span class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $sc['color']) }} {{ $application->status === 'pending' ? 'animate-pulse' : '' }}"></span>
                {{ $sc['label'] }}
            </span>
        </div>
    </div>

    {{-- Info Requested Alert --}}
    @if($application->status === 'info_requested' && $application->info_requested_note)
        <div class="flex items-start gap-3 bg-orange-50 border border-orange-100 rounded-2xl p-5">
            <svg class="w-5 h-5 text-orange-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <p class="text-sm font-medium text-orange-700 mb-1">Additional information required</p>
                <p class="text-sm text-orange-600 font-light leading-relaxed">{{ $application->info_requested_note }}</p>
            </div>
        </div>
    @endif

    {{-- Loan Details Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Loan Details</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y divide-gray-50">
            <div class="p-5 text-center">
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-1">Amount Requested</p>
                <p class="text-lg font-semibold text-[#1B4F72]">ZMW {{ number_format($application->amount_requested, 0) }}</p>
            </div>
            @if($application->amount_approved)
            <div class="p-5 text-center">
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-1">Amount Approved</p>
                <p class="text-lg font-semibold text-emerald-600">ZMW {{ number_format($application->amount_approved, 0) }}</p>
            </div>
            @endif
            <div class="p-5 text-center">
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-1">Tenure</p>
                <p class="text-lg font-semibold text-gray-700">{{ $application->tenure_months }} months</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-1">Interest Rate</p>
                <p class="text-lg font-semibold text-gray-700">{{ $application->interest_rate }}%/mo</p>
            </div>
            @if($application->monthly_repayment)
            <div class="p-5 text-center">
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-1">Monthly Repayment</p>
                <p class="text-lg font-semibold text-gray-700">ZMW {{ number_format($application->monthly_repayment, 2) }}</p>
            </div>
            @endif
            @if($application->disbursed_at)
            <div class="p-5 text-center">
                <p class="text-[11px] text-gray-400 uppercase tracking-wider mb-1">Disbursed</p>
                <p class="text-sm font-semibold text-gray-700">{{ $application->disbursed_at->format('d M Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Repayments (if disbursed) --}}
    @if($application->isDisbursed() && $application->repayments->count())
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Repayments</h3>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Outstanding</p>
                    <p class="text-sm font-semibold text-[#1B4F72]">ZMW {{ number_format($application->outstandingBalance(), 2) }}</p>
                </div>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($application->repayments->sortByDesc('payment_date') as $repayment)
                    <div class="flex items-center justify-between px-6 py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700">ZMW {{ number_format($repayment->amount, 2) }}</p>
                            <p class="text-xs text-gray-400">{{ $repayment->payment_date?->format('d M Y') ?? 'Pending' }}</p>
                        </div>
                        <span class="text-xs text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full font-medium">Paid</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Document checklist --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m-7-8h8m-9 12h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Document Checklist</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($documentChecklist as $item)
                <div class="px-6 py-3 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $item['label'] }}</p>
                        <p class="text-xs text-gray-400">{{ $item['required'] ? 'Required' : 'Recommended' }}</p>
                    </div>
                    @if($item['approved'])
                        <span class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-full font-medium">Approved</span>
                    @elseif($item['needs_resubmission'])
                        <span class="text-xs bg-orange-50 text-orange-600 border border-orange-100 px-2.5 py-1 rounded-full font-medium">Needs update</span>
                    @elseif($item['submitted'])
                        <span class="text-xs bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-full font-medium">Submitted</span>
                    @elseif($item['required'])
                        <span class="text-xs bg-red-50 text-red-600 border border-red-100 px-2.5 py-1 rounded-full font-medium">Missing</span>
                    @else
                        <span class="text-xs bg-gray-50 text-gray-500 border border-gray-100 px-2.5 py-1 rounded-full font-medium">Optional</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Upload document (when info requested) --}}
    @if($application->status === 'info_requested')
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-4">Upload Additional Document</h3>
            <form wire:submit.prevent="uploadDocument" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Document Type</label>
                    <select wire:model="replacement_document_type"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] mb-4">
                        @foreach($requestedDocumentTypes as $type => $label)
                            <option value="{{ $type }}">{{ $label }} (requested)</option>
                        @endforeach
                        <option value="other">Other supporting document</option>
                    </select>
                    @error('replacement_document_type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror

                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Document File (PDF / JPG / PNG, max 5 MB)</label>
                    <input wire:model="extra_document" type="file" accept=".pdf,.jpg,.jpeg,.png"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0
                               file:text-sm file:font-medium file:bg-[#1B4F72]/10 file:text-[#1B4F72]
                               hover:file:bg-[#1B4F72]/20 transition-all">
                    @error('extra_document') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1B4F72] text-white text-sm font-medium rounded-xl hover:bg-[#154060] transition-colors">
                    <span wire:loading.remove>Upload Document</span>
                    <span wire:loading>Uploading…</span>
                </button>
            </form>
        </div>
    @endif

    {{-- Documents --}}
        @if($documents->count())
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Documents</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($documents as $doc)
                    <div class="flex items-center justify-between px-6 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $doc->label() }}</p>
                                <p class="text-xs text-gray-400">{{ $doc->original_filename }}</p>
                                @if($doc->review_notes)
                                    <p class="text-xs text-gray-500 mt-1">{{ $doc->review_notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('documents.preview', $doc) }}" target="_blank"
                                class="text-xs text-[#1B4F72] bg-blue-50 px-2.5 py-1 rounded-full font-medium hover:bg-blue-100 transition-colors">
                                Preview
                            </a>
                            <a href="{{ route('documents.download', $doc) }}"
                                class="text-xs text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full font-medium hover:bg-gray-200 transition-colors">
                                Download
                            </a>
                            <span class="text-xs border px-2.5 py-1 rounded-full font-medium {{ $doc->statusClasses() }}">
                                {{ $doc->statusLabel() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Admin notes / rejection reason --}}
    @if($application->rejection_reason)
        <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-2xl p-5">
            <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <div>
                <p class="text-sm font-medium text-red-700 mb-1">Application Rejected</p>
                <p class="text-sm text-red-600 font-light">{{ $application->rejection_reason }}</p>
            </div>
        </div>
    @endif

</div>
