@php
$statusConfig = [
    'pending' => ['color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'label' => 'Pending'],
    'under_review' => ['color' => 'text-sky-600', 'bg' => 'bg-sky-50', 'border' => 'border-sky-100', 'label' => 'Under Review'],
    'approved' => ['color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'label' => 'Approved'],
    'rejected' => ['color' => 'text-red-500', 'bg' => 'bg-red-50', 'border' => 'border-red-100', 'label' => 'Rejected'],
    'disbursed' => ['color' => 'text-green-600', 'bg' => 'bg-green-50', 'border' => 'border-green-100', 'label' => 'Disbursed'],
    'info_requested' => ['color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'border' => 'border-orange-100', 'label' => 'Info Requested'],
];
$sc = $statusConfig[$application->status] ?? $statusConfig['pending'];
$documentChecklist = $application->documentChecklist();
$documents = $application->activeDocuments();
@endphp

<div x-data="portalDocumentWorkspace()"
     x-on:portal-action-finished.window="closeModal()"
     x-on:keydown.escape.window="closeModal(); closeViewer()"
     class="max-w-7xl mx-auto space-y-6 p-6 lg:p-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.applications') }}" class="portal-action inline-flex items-center gap-1.5 text-xs text-gray-400 transition-colors hover:text-gray-600">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Applications
            </a>
            <h2 class="mt-3 text-2xl font-semibold text-gray-800">{{ $application->reference }}</h2>
            <p class="mt-1 text-sm font-light text-gray-400">{{ $application->loanProduct->name }} - Applied {{ $application->created_at->format('d M Y') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium {{ $sc['bg'] }} {{ $sc['color'] }} {{ $sc['border'] }}">
                <span class="h-2 w-2 rounded-full {{ str_replace('text-', 'bg-', $sc['color']) }}"></span>
                {{ $sc['label'] }}
            </span>

            @if($application->status === 'pending')
                <button wire:click="markUnderReview"
                        wire:loading.attr="disabled"
                        wire:target="markUnderReview"
                        class="portal-action inline-flex items-center gap-2 rounded-2xl border border-sky-100 bg-sky-50 px-4 py-2 text-sm font-medium text-sky-700 hover:bg-sky-100">
                    <span wire:loading.remove wire:target="markUnderReview">Mark Under Review</span>
                    <span wire:loading.flex wire:target="markUnderReview" class="items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Updating...
                    </span>
                </button>
            @endif
        </div>
    </div>

    <div wire:loading.flex wire:target="approve,reject,requestInfo,disburse,markUnderReview" class="items-center gap-3 rounded-2xl border border-[#4EA8D9]/15 bg-[#4EA8D9]/10 px-4 py-3 text-sm text-[#1B4F72]">
        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Processing the application update...
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(0,0.95fr)]">
        <div class="space-y-6">
            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#4EA8D9]/10 text-[#4EA8D9]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Personal Details</h3>
                            <p class="text-xs text-gray-400">Core KYC and residential information.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-3 px-5 py-4 text-sm sm:grid-cols-2 sm:gap-x-6">
                    @php $u = $application->customer->user; $c = $application->customer; @endphp
                    <span class="text-gray-400">Name</span><span class="font-medium text-gray-700">{{ $u->name }}</span>
                    <span class="text-gray-400">Email</span><span class="font-medium text-gray-700">{{ $u->email }}</span>
                    <span class="text-gray-400">Phone</span><span class="font-medium text-gray-700">{{ $u->phone }}</span>
                    <span class="text-gray-400">NRC</span><span class="font-medium text-gray-700">{{ $c->nrc_number }}</span>
                    <span class="text-gray-400">Date of Birth</span><span class="font-medium text-gray-700">{{ $c->date_of_birth?->format('d M Y') }}</span>
                    <span class="text-gray-400">Gender</span><span class="font-medium text-gray-700">{{ ucfirst($c->gender ?? '-') }}</span>
                    <span class="text-gray-400">Marital Status</span><span class="font-medium text-gray-700">{{ ucfirst($c->marital_status ?? '-') }}</span>
                    <span class="text-gray-400">City</span><span class="font-medium text-gray-700">{{ $c->city }}, {{ $c->province }}</span>
                    <span class="border-t border-gray-100 pt-3 text-gray-400 sm:col-span-2">Address</span>
                    <span class="border-t border-gray-100 pt-3 font-medium text-gray-700 sm:col-span-2">{{ $c->residential_address }}</span>
                </div>
            </div>

            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#4EA8D9]/10 text-[#4EA8D9]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Employment</h3>
                            <p class="text-xs text-gray-400">Income, employer, and banking summary.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-3 px-5 py-4 text-sm sm:grid-cols-2 sm:gap-x-6">
                    <span class="text-gray-400">Employer</span><span class="font-medium text-gray-700">{{ $c->employer_name ?? '-' }}</span>
                    <span class="text-gray-400">Job Title</span><span class="font-medium text-gray-700">{{ $c->job_title ?? '-' }}</span>
                    <span class="text-gray-400">Monthly Income</span><span class="font-semibold text-[#166534]">ZMW {{ number_format($c->monthly_income ?? 0, 0) }}</span>
                    <span class="text-gray-400">Since</span><span class="font-medium text-gray-700">{{ $c->employment_date?->format('d M Y') ?? '-' }}</span>
                    <span class="text-gray-400">Bank</span><span class="font-medium text-gray-700">{{ $c->bank_name ?? '-' }}</span>
                    <span class="text-gray-400">Account</span><span class="font-medium text-gray-700">{{ $c->bank_account_number ?? '-' }}</span>
                </div>
            </div>

            @if($application->collaterals->count())
                <div class="portal-panel rounded-3xl overflow-hidden">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#F39C12]/10 text-[#F39C12]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Collateral</h3>
                                <p class="text-xs text-gray-400">Asset details supplied with the application.</p>
                            </div>
                        </div>
                    </div>

                    @foreach($application->collaterals as $col)
                        <div class="grid grid-cols-1 gap-y-3 border-t border-gray-100 px-5 py-4 text-sm first:border-t-0 sm:grid-cols-2 sm:gap-x-6">
                            <span class="text-gray-400">Type</span><span class="font-medium text-gray-700">{{ ucfirst($col->type) }}</span>
                            <span class="text-gray-400">Est. Value</span><span class="font-semibold text-[#166534]">ZMW {{ number_format($col->estimated_value, 0) }}</span>
                            <span class="text-gray-400">Reg. No.</span><span class="font-medium text-gray-700">{{ $col->registration_number ?? '-' }}</span>
                            <span class="border-t border-gray-100 pt-3 text-gray-400 sm:col-span-2">Description</span>
                            <span class="border-t border-gray-100 pt-3 font-medium text-gray-700 sm:col-span-2">{{ $col->description }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#4EA8D9]/10 text-[#4EA8D9]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Documents</h3>
                            <p class="text-xs text-gray-400">Preview, review, and guide the customer without leaving the screen.</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($documents as $doc)
                        @php
                            $previewKind = strtolower(pathinfo($doc->original_filename, PATHINFO_EXTENSION)) === 'pdf' ? 'pdf' : 'image';
                            $previewTitle = $doc->label().' - '.$doc->original_filename;
                        @endphp
                        <div class="space-y-3 px-5 py-4" wire:key="doc-{{ $doc->id }}">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $doc->label() }}</p>
                                    <p class="text-xs text-gray-400">{{ $doc->original_filename }}</p>
                                    @if($doc->reviewed_at)
                                        <p class="mt-1 text-[11px] text-gray-400">Reviewed {{ $doc->reviewed_at->format('d M Y H:i') }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium {{ $doc->statusClasses() }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ str_contains($doc->statusClasses(), 'emerald') ? 'bg-emerald-500' : (str_contains($doc->statusClasses(), 'orange') ? 'bg-orange-500' : 'bg-slate-400') }}"></span>
                                    {{ $doc->statusLabel() }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @if($doc->canPreviewInline())
                                    <button type="button"
                                            x-on:click="openViewer('{{ route('documents.preview', $doc) }}', @js($previewTitle), '{{ $previewKind }}')"
                                            class="portal-action inline-flex items-center gap-2 rounded-full border border-[#4EA8D9]/15 bg-[#4EA8D9]/10 px-3 py-1.5 text-xs font-medium text-[#1B4F72] hover:bg-[#4EA8D9]/15">
                                        Preview
                                    </button>
                                @endif
                                <a href="{{ route('documents.download', $doc) }}"
                                   class="portal-action inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-200">
                                    Download
                                </a>
                                <button wire:click="approveDocument({{ $doc->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="approveDocument({{ $doc->id }})"
                                        class="portal-action inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-100">
                                    <span wire:loading.remove wire:target="approveDocument({{ $doc->id }})">Approve</span>
                                    <span wire:loading.flex wire:target="approveDocument({{ $doc->id }})" class="items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Saving
                                    </span>
                                </button>
                                <button wire:click="requestDocumentResubmission({{ $doc->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="requestDocumentResubmission({{ $doc->id }})"
                                        class="portal-action inline-flex items-center gap-2 rounded-full border border-orange-100 bg-orange-50 px-3 py-1.5 text-xs font-medium text-orange-700 hover:bg-orange-100">
                                    <span wire:loading.remove wire:target="requestDocumentResubmission({{ $doc->id }})">Request Update</span>
                                    <span wire:loading.flex wire:target="requestDocumentResubmission({{ $doc->id }})" class="items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Sending
                                    </span>
                                </button>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-[11px] font-medium uppercase tracking-[0.16em] text-gray-500">Reviewer Note</label>
                                <textarea wire:model.blur="documentNotes.{{ $doc->id }}"
                                          rows="2"
                                          class="w-full rounded-2xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 transition-all resize-none focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10"
                                          placeholder="Add context for approval or tell the customer exactly what needs to be corrected..."></textarea>
                                @error("documentNotes.$doc->id")
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                @if($doc->review_notes)
                                    <p class="mt-2 text-xs text-gray-500">{{ $doc->review_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="px-5 py-5 text-sm text-gray-400">No documents uploaded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#166534]/10 text-[#166534]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m-7-8h8m-9 12h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Document Checklist</h3>
                            <p class="text-xs text-gray-400">Use this to spot missing or updated files quickly.</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($documentChecklist as $item)
                        <div class="flex items-start justify-between gap-3 px-5 py-3.5">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $item['label'] }}</p>
                                <p class="text-xs text-gray-400">{{ $item['required'] ? 'Required' : 'Recommended' }}</p>
                            </div>
                            @if($item['approved'])
                                <span class="rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600">Approved</span>
                            @elseif($item['needs_resubmission'])
                                <span class="rounded-full border border-orange-100 bg-orange-50 px-2.5 py-1 text-xs font-medium text-orange-600">Needs update</span>
                            @elseif($item['submitted'])
                                <span class="rounded-full border border-sky-100 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-600">Submitted</span>
                            @elseif($item['required'])
                                <span class="rounded-full border border-red-100 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">Missing</span>
                            @else
                                <span class="rounded-full border border-gray-100 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-500">Optional</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#166534]/10 text-[#166534]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Loan Details</h3>
                            <p class="text-xs text-gray-400">Use these figures during approval and disbursement.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 px-5 py-4 text-sm">
                    <div class="flex justify-between gap-3"><span class="text-gray-400">Requested</span><span class="font-semibold text-[#166534]">ZMW {{ number_format($application->amount_requested, 0) }}</span></div>
                    @if($application->amount_approved)
                        <div class="flex justify-between gap-3"><span class="text-gray-400">Approved</span><span class="font-semibold text-emerald-600">ZMW {{ number_format($application->amount_approved, 0) }}</span></div>
                    @endif
                    <div class="flex justify-between gap-3"><span class="text-gray-400">Tenure</span><span class="font-medium text-gray-700">{{ $application->tenure_months }} months</span></div>
                    <div class="flex justify-between gap-3"><span class="text-gray-400">Rate</span><span class="font-medium text-gray-700">{{ $application->interest_rate }}%/mo</span></div>
                    @if($application->monthly_repayment)
                        <div class="flex justify-between gap-3 border-t border-gray-100 pt-3"><span class="font-medium text-gray-500">Monthly Payment</span><span class="font-semibold text-gray-800">ZMW {{ number_format($application->monthly_repayment, 2) }}</span></div>
                    @endif
                    @if($application->purpose)
                        <div class="border-t border-gray-100 pt-3">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Purpose</p>
                            <p class="mt-1 text-sm text-gray-700">{{ $application->purpose }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if(!in_array($application->status, ['disbursed', 'closed', 'cancelled']))
                <div class="portal-panel rounded-3xl p-5">
                    <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Actions</h3>
                    <p class="mt-1 text-xs text-gray-400">Every decision keeps the reviewer on the page and shows progress clearly.</p>

                    <div class="mt-4 space-y-3">
                        @if(in_array($application->status, ['pending', 'under_review', 'info_requested']))
                            <button x-on:click="openModal('approve')"
                                    class="portal-action flex w-full items-center justify-center gap-2 rounded-2xl bg-[#166534] py-3 text-sm font-semibold text-white shadow-lg shadow-[#166534]/20 hover:bg-[#14532d]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve
                            </button>
                            <button x-on:click="openModal('info')"
                                    class="portal-action flex w-full items-center justify-center gap-2 rounded-2xl border border-orange-100 bg-orange-50 py-3 text-sm font-medium text-orange-600 hover:bg-orange-100">
                                Request More Info
                            </button>
                            <button x-on:click="openModal('reject')"
                                    class="portal-action flex w-full items-center justify-center gap-2 rounded-2xl border border-red-100 bg-red-50 py-3 text-sm font-medium text-red-600 hover:bg-red-100">
                                Reject
                            </button>
                        @endif

                        @if($application->status === 'approved')
                            <button x-on:click="openModal('disburse')"
                                    class="portal-action flex w-full items-center justify-center gap-2 rounded-2xl bg-[#1B4F72] py-3 text-sm font-semibold text-white shadow-lg shadow-[#1B4F72]/20 hover:bg-[#163f5d]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Mark as Disbursed
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            @if($application->isDisbursed())
                <div class="portal-panel rounded-3xl p-5">
                    <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500">Repayment Summary</h3>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between gap-3"><span class="text-gray-400">Total Repaid</span><span class="font-medium text-emerald-600">ZMW {{ number_format($application->totalRepaid(), 2) }}</span></div>
                        <div class="flex justify-between gap-3"><span class="text-gray-400">Outstanding</span><span class="font-semibold text-[#166534]">ZMW {{ number_format($application->outstandingBalance(), 2) }}</span></div>
                        <div class="flex justify-between gap-3"><span class="text-gray-400">Due Date</span><span class="font-medium text-gray-700">{{ $application->due_date?->format('d M Y') }}</span></div>
                    </div>
                    <a href="{{ route('admin.repayments') }}" class="portal-action mt-4 inline-flex text-xs font-medium text-[#1B4F72] hover:underline">Record repayment</a>
                </div>
            @endif

            @if($application->admin_notes)
                <div class="portal-panel rounded-3xl bg-slate-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-[0.16em] text-gray-500">Admin Notes</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $application->admin_notes }}</p>
                </div>
            @endif

            @if($application->rejection_reason)
                <div class="portal-panel rounded-3xl border border-red-100 bg-red-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-[0.16em] text-red-500">Rejection Reason</p>
                    <p class="mt-2 text-sm text-red-700">{{ $application->rejection_reason }}</p>
                </div>
            @endif
        </div>
    </div>

    <div x-cloak x-show="modal === 'approve'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="portal-backdrop absolute inset-0 bg-[#071520]/70" x-on:click="closeModal()"></div>
        <div class="relative w-full max-w-md rounded-[1.75rem] border border-white/10 bg-white p-7 shadow-2xl shadow-black/20">
            <h2 class="text-lg font-semibold text-gray-800">Approve Application</h2>
            <p class="mt-1 text-sm text-gray-400">Confirm the amount, rate, and tenure before the customer sees the decision.</p>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Amount Approved (ZMW)</label>
                    <input wire:model="approveAmount" type="number" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10">
                    @error('approveAmount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Interest Rate (%/mo)</label>
                        <input wire:model="approveRate" type="number" step="0.01" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-gray-600">Tenure (months)</label>
                        <input wire:model="approveTenure" type="number" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Admin Notes (internal)</label>
                    <textarea wire:model="approveNotes" rows="2" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all resize-none focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10"></textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" x-on:click="closeModal()" class="portal-action flex-1 rounded-2xl border border-gray-200 py-3 text-sm font-medium text-gray-500 hover:bg-gray-50">Cancel</button>
                <button wire:click="approve"
                        wire:loading.attr="disabled"
                        wire:target="approve"
                        class="portal-action flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#166534] py-3 text-sm font-semibold text-white hover:bg-[#14532d]">
                    <span wire:loading.remove wire:target="approve">Confirm Approval</span>
                    <span wire:loading.flex wire:target="approve" class="items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Approving...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div x-cloak x-show="modal === 'reject'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="portal-backdrop absolute inset-0 bg-[#071520]/70" x-on:click="closeModal()"></div>
        <div class="relative w-full max-w-md rounded-[1.75rem] border border-white/10 bg-white p-7 shadow-2xl shadow-black/20">
            <h2 class="text-lg font-semibold text-gray-800">Reject Application</h2>
            <p class="mt-1 text-sm text-gray-400">This reason is visible to the customer, so keep it specific and clear.</p>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Rejection Reason <span class="text-red-400">*</span></label>
                    <textarea wire:model="rejectReason" rows="3" placeholder="Insufficient income for the requested amount..." class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all resize-none focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100"></textarea>
                    @error('rejectReason') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Internal Notes</label>
                    <textarea wire:model="rejectNotes" rows="2" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all resize-none focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10"></textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" x-on:click="closeModal()" class="portal-action flex-1 rounded-2xl border border-gray-200 py-3 text-sm font-medium text-gray-500 hover:bg-gray-50">Cancel</button>
                <button wire:click="reject"
                        wire:loading.attr="disabled"
                        wire:target="reject"
                        class="portal-action flex flex-1 items-center justify-center gap-2 rounded-2xl bg-red-600 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    <span wire:loading.remove wire:target="reject">Confirm Rejection</span>
                    <span wire:loading.flex wire:target="reject" class="items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Rejecting...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div x-cloak x-show="modal === 'info'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="portal-backdrop absolute inset-0 bg-[#071520]/70" x-on:click="closeModal()"></div>
        <div class="relative w-full max-w-md rounded-[1.75rem] border border-white/10 bg-white p-7 shadow-2xl shadow-black/20">
            <h2 class="text-lg font-semibold text-gray-800">Request More Information</h2>
            <p class="mt-1 text-sm text-gray-400">Explain exactly what the customer should upload or correct.</p>

            <div class="mt-5">
                <label class="mb-1 block text-xs font-medium text-gray-600">Note to Customer <span class="text-red-400">*</span></label>
                <textarea wire:model="infoNote" rows="4" placeholder="Please upload a clearer copy of the NRC and a recent payslip..." class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all resize-none focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10"></textarea>
                @error('infoNote') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" x-on:click="closeModal()" class="portal-action flex-1 rounded-2xl border border-gray-200 py-3 text-sm font-medium text-gray-500 hover:bg-gray-50">Cancel</button>
                <button wire:click="requestInfo"
                        wire:loading.attr="disabled"
                        wire:target="requestInfo"
                        class="portal-action flex flex-1 items-center justify-center gap-2 rounded-2xl bg-orange-500 py-3 text-sm font-semibold text-white hover:bg-orange-600">
                    <span wire:loading.remove wire:target="requestInfo">Send Request</span>
                    <span wire:loading.flex wire:target="requestInfo" class="items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div x-cloak x-show="modal === 'disburse'" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="portal-backdrop absolute inset-0 bg-[#071520]/70" x-on:click="closeModal()"></div>
        <div class="relative w-full max-w-md rounded-[1.75rem] border border-white/10 bg-white p-7 shadow-2xl shadow-black/20">
            <h2 class="text-lg font-semibold text-gray-800">Mark as Disbursed</h2>
            <p class="mt-1 text-sm text-gray-400">Use the actual release date so repayment schedules stay accurate.</p>

            <div class="mt-5 space-y-4">
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Disbursement Date</label>
                    <input wire:model="disburseDate" type="date" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10">
                    @error('disburseDate') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-xs font-medium text-gray-600">Notes</label>
                    <textarea wire:model="disburseNotes" rows="2" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm transition-all resize-none focus:border-[#166534] focus:outline-none focus:ring-2 focus:ring-[#166534]/10"></textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" x-on:click="closeModal()" class="portal-action flex-1 rounded-2xl border border-gray-200 py-3 text-sm font-medium text-gray-500 hover:bg-gray-50">Cancel</button>
                <button wire:click="disburse"
                        wire:loading.attr="disabled"
                        wire:target="disburse"
                        class="portal-action flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#1B4F72] py-3 text-sm font-semibold text-white hover:bg-[#163f5d]">
                    <span wire:loading.remove wire:target="disburse">Confirm Disbursement</span>
                    <span wire:loading.flex wire:target="disburse" class="items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Disbursing...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div x-cloak
         x-show="viewerOpen"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="portal-backdrop absolute inset-0 bg-[#071520]/75" x-on:click="closeViewer()"></div>

        <div class="relative flex max-h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-[1.75rem] border border-white/10 bg-[#071520] shadow-2xl shadow-black/40">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4 text-white">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold" x-text="viewerTitle"></p>
                    <p class="text-xs text-slate-400">Document preview</p>
                </div>
                <div class="ml-4 flex items-center gap-2">
                    <a :href="viewerUrl"
                       target="_blank"
                       class="portal-action inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/10">
                        Open in new tab
                    </a>
                    <button type="button"
                            x-on:click="closeViewer()"
                            class="portal-action inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white hover:bg-white/10">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="min-h-0 flex-1 bg-slate-950/30 p-3 sm:p-4">
                <template x-if="viewerKind === 'pdf'">
                    <iframe :src="viewerUrl" class="h-[72vh] w-full rounded-2xl bg-white"></iframe>
                </template>

                <template x-if="viewerKind === 'image'">
                    <div class="flex h-[72vh] items-center justify-center rounded-2xl bg-[#020b12] p-4">
                        <img :src="viewerUrl" :alt="viewerTitle" class="max-h-full max-w-full rounded-2xl object-contain">
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
