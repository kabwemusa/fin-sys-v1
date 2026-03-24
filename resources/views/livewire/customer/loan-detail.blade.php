@php
$statusConfig = [
    'pending' => ['color' => 'text-amber-600', 'bg' => 'bg-[#FEF9E1]', 'border' => 'border-amber-100', 'label' => 'Pending'],
    'under_review' => ['color' => 'text-sky-600', 'bg' => 'bg-sky-50', 'border' => 'border-sky-100', 'label' => 'Under Review'],
    'approved' => ['color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'label' => 'Approved'],
    'rejected' => ['color' => 'text-red-500', 'bg' => 'bg-red-50', 'border' => 'border-red-100', 'label' => 'Rejected'],
    'disbursed' => ['color' => 'text-[#E98C00]', 'bg' => 'bg-[#FEF9E1]', 'border' => 'border-[#E98C00]', 'label' => 'Disbursed'],
    'info_requested' => ['color' => 'text-[#E98C00]', 'bg' => 'bg-[#FEF9E1]', 'border' => 'border-[#E98C00]', 'label' => 'Info Requested'],
    'closed' => ['color' => 'text-gray-500', 'bg' => 'bg-[#FEF9E1]', 'border' => 'border-gray-200', 'label' => 'Closed'],
    'cancelled' => ['color' => 'text-gray-400', 'bg' => 'bg-[#FEF9E1]', 'border' => 'border-gray-100', 'label' => 'Cancelled'],
];
$sc = $statusConfig[$application->status] ?? $statusConfig['pending'];
$documents = $application->activeDocuments();
@endphp

<div x-data="portalDocumentWorkspace()"
     x-on:document-uploaded.window="clearUploadSelection()"
     x-on:keydown.escape.window="closeViewer()"
     x-on:beforeunload.window="clearUploadSelection()"
     class="max-w-6xl mx-auto space-y-6 p-6 lg:p-8">

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('portal.loans') }}" class="portal-action inline-flex items-center gap-1.5 text-xs text-gray-400 transition-colors hover:text-gray-600">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to My Loans
            </a>
            <h2 class="mt-3 text-2xl font-semibold text-gray-800">{{ $application->reference }}</h2>
            <p class="mt-1 text-sm font-light text-gray-400">{{ $application->loanProduct->name }} - Applied {{ $application->created_at->format('d M Y') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium {{ $sc['bg'] }} {{ $sc['color'] }} {{ $sc['border'] }}">
                <span class="h-2 w-2 rounded-full {{ str_replace('text-', 'bg-', $sc['color']) }} {{ $application->status === 'pending' ? 'animate-pulse' : '' }}"></span>
                {{ $sc['label'] }}
            </span>
            @if($application->status === 'info_requested')
                <span class="inline-flex items-center gap-2 rounded-full border border-[#E98C00] bg-[#FEF9E1] px-4 py-2 text-sm font-medium text-[#E98C00]">
                    <span class="h-2 w-2 rounded-full bg-[#E98C00] animate-pulse"></span>
                    Action needed from you
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="portal-panel rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
            <div class="flex items-center gap-3">
                <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($application->status === 'info_requested' && $application->info_requested_note)
        <div class="portal-panel rounded-3xl border border-[#E98C00] bg-[#FEF9E1] p-5">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-[#E98C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-[#C97A00]">Additional information required</p>
                    <p class="mt-1 text-sm leading-relaxed text-[#E98C00]">{{ $application->info_requested_note }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(0,0.9fr)]">
        <div class="space-y-6">
            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#E98C00]/10 text-[#E98C00]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500">Loan Details</h3>
                            <p class="text-xs text-gray-400">Everything tied to this application at a glance.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-px bg-[#FEF9E1] sm:grid-cols-4">
                    <div class="bg-white p-5 text-center">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Requested</p>
                        <p class="mt-2 text-lg font-semibold text-[#E98C00]">ZMW {{ number_format($application->amount_requested, 0) }}</p>
                    </div>
                    <div class="bg-white p-5 text-center">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Tenure</p>
                        <p class="mt-2 text-lg font-semibold text-gray-700">{{ $application->tenure_months }} months</p>
                    </div>
                    <div class="bg-white p-5 text-center">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Interest</p>
                        <p class="mt-2 text-lg font-semibold text-gray-700">{{ $application->interest_rate }}%/mo</p>
                    </div>
                    <div class="bg-white p-5 text-center">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Monthly</p>
                        <p class="mt-2 text-lg font-semibold text-gray-700">
                            {{ $application->monthly_repayment ? 'ZMW '.number_format($application->monthly_repayment, 2) : 'Pending' }}
                        </p>
                    </div>
                    @if($application->amount_approved)
                        <div class="bg-white p-5 text-center sm:col-span-2">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Approved Amount</p>
                            <p class="mt-2 text-lg font-semibold text-emerald-600">ZMW {{ number_format($application->amount_approved, 0) }}</p>
                        </div>
                    @endif
                    @if($application->disbursed_at)
                        <div class="bg-white p-5 text-center sm:col-span-2">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-gray-400">Disbursed</p>
                            <p class="mt-2 text-lg font-semibold text-gray-700">{{ $application->disbursed_at->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($application->isDisbursed() && $application->repayments->count())
                <div class="portal-panel rounded-3xl overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#E98C00]/10 text-[#E98C00]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500">Repayments</h3>
                                <p class="text-xs text-gray-400">Your latest payments and remaining balance.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-400">Outstanding</p>
                            <p class="text-sm font-semibold text-[#E98C00]">ZMW {{ number_format($application->outstandingBalance(), 2) }}</p>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($application->repayments->sortByDesc('payment_date') as $repayment)
                            <div class="flex items-center justify-between px-6 py-3.5">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">ZMW {{ number_format($repayment->amount, 2) }}</p>
                                    <p class="text-xs text-gray-400">{{ $repayment->payment_date?->format('d M Y') ?? 'Pending' }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Paid
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($documents->count())
                <div class="portal-panel rounded-3xl overflow-hidden">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#E98C00]/10 text-[#E98C00]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500">Documents</h3>
                                <p class="text-xs text-gray-400">Preview and download everything you have submitted.</p>
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($documents as $doc)
                            @php
                                $previewKind = strtolower(pathinfo($doc->original_filename, PATHINFO_EXTENSION)) === 'pdf' ? 'pdf' : 'image';
                                $previewTitle = $doc->label().' - '.$doc->original_filename;
                            @endphp
                            <div class="flex flex-col gap-4 px-6 py-4 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#FEF9E1] text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ $doc->label() }}</p>
                                        <p class="text-xs text-gray-400">{{ $doc->original_filename }}</p>
                                        @if($doc->review_notes)
                                            <p class="mt-1 text-xs text-gray-500">{{ $doc->review_notes }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    @if($doc->canPreviewInline())
                                        <button type="button"
                                                x-on:click="openViewer('{{ route('documents.preview', $doc) }}', @js($previewTitle), '{{ $previewKind }}')"
                                                class="portal-action inline-flex items-center gap-2 rounded-full border border-[#E98C00]/15 bg-[#E98C00]/10 px-3 py-1.5 text-xs font-medium text-[#E98C00] hover:bg-[#E98C00]/15">
                                            Preview
                                        </button>
                                    @endif
                                    <a href="{{ route('documents.download', $doc) }}"
                                       class="portal-action inline-flex items-center gap-2 rounded-full border border-slate-200 bg-[#FEF9E1] px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-200">
                                        Download
                                    </a>
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-medium {{ $doc->statusClasses() }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ str_contains($doc->statusClasses(), 'emerald') ? 'bg-emerald-500' : (str_contains($doc->statusClasses(), 'orange') ? 'bg-[#E98C00]' : 'bg-slate-400') }}"></span>
                                        {{ $doc->statusLabel() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($application->rejection_reason)
                <div class="portal-panel rounded-3xl border border-red-100 bg-red-50 p-5">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-700">Application Rejected</p>
                            <p class="mt-1 text-sm text-red-600">{{ $application->rejection_reason }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="portal-panel rounded-3xl overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#E98C00]/10 text-[#E98C00]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m-7-8h8m-9 12h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500">Document Checklist</h3>
                            <p class="text-xs text-gray-400">Required items move your application forward faster.</p>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($documentChecklist as $item)
                        <div class="flex items-start justify-between gap-3 px-6 py-3.5">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $item['label'] }}</p>
                                <p class="text-xs text-gray-400">{{ $item['required'] ? 'Required' : 'Recommended' }}</p>
                            </div>
                            @if($item['approved'])
                                <span class="rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600">Approved</span>
                            @elseif($item['needs_resubmission'])
                                <span class="rounded-full border border-[#E98C00] bg-[#FEF9E1] px-2.5 py-1 text-xs font-medium text-[#E98C00]">Needs update</span>
                            @elseif($item['submitted'])
                                <span class="rounded-full border border-sky-100 bg-sky-50 px-2.5 py-1 text-xs font-medium text-sky-600">Submitted</span>
                            @elseif($item['required'])
                                <span class="rounded-full border border-red-100 bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">Missing</span>
                            @else
                                <span class="rounded-full border border-gray-100 bg-[#FEF9E1] px-2.5 py-1 text-xs font-medium text-gray-500">Optional</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @if($application->status === 'info_requested')
                <div class="portal-panel rounded-3xl overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-[#E98C00]/10 text-[#E98C00]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.16em] text-gray-500">Upload Additional Document</h3>
                                <p class="text-xs text-gray-400">Stay on this page, preview the file, then submit.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 p-6">
                        <div wire:loading.flex wire:target="uploadDocument" class="items-center gap-3 rounded-2xl border border-[#E98C00]/15 bg-[#E98C00]/10 px-4 py-3 text-sm text-[#E98C00]">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Uploading your document and refreshing the checklist...
                        </div>

                        <form wire:submit.prevent="uploadDocument" class="space-y-4">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-600">Document Type</label>
                                <select wire:model="replacement_document_type"
                                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm text-gray-700 transition-all focus:border-[#E98C00] focus:outline-none focus:ring-2 focus:ring-[#E98C00]/10">
                                    @foreach($requestedDocumentTypes as $type => $label)
                                        <option value="{{ $type }}">{{ $label }} (requested)</option>
                                    @endforeach
                                    <option value="other">Other supporting document</option>
                                </select>
                                @error('replacement_document_type')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-gray-600">Document File (PDF / JPG / PNG, max 5 MB)</label>
                                <input x-ref="uploadInput"
                                       x-on:change="handleUploadSelection($event)"
                                       wire:model="extra_document"
                                       type="file"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-2xl file:border-0 file:bg-[#E98C00]/10 file:px-4 file:py-3 file:text-sm file:font-medium file:text-[#E98C00] hover:file:bg-[#E98C00]/15">
                                @error('extra_document')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-cloak x-show="uploadPreviewUrl" class="rounded-2xl border border-[#E98C00]/15 bg-[#FEF9E1] p-4">
                                <p class="text-sm font-medium text-gray-700" x-text="uploadPreviewTitle"></p>
                                <p class="mt-1 text-xs text-gray-400">Preview it before sending so you know the right file is attached.</p>
                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <button type="button"
                                            x-on:click="openUploadPreview()"
                                            class="portal-action inline-flex items-center gap-2 rounded-full border border-[#E98C00]/20 bg-[#E98C00]/10 px-4 py-2 text-xs font-medium text-[#E98C00] hover:bg-[#E98C00]/15">
                                        Preview selected file
                                    </button>
                                    <span class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-500" x-text="uploadPreviewKind === 'pdf' ? 'PDF preview ready' : 'Image preview ready'"></span>
                                </div>
                            </div>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="uploadDocument"
                                    class="portal-action inline-flex items-center gap-2 rounded-2xl bg-[#E98C00] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[#E98C00]/20 transition-all hover:bg-[#C97A00]">
                                <span wire:loading.remove wire:target="uploadDocument">Upload Document</span>
                                <span wire:loading.flex wire:target="uploadDocument" class="items-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Uploading...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div x-cloak
         x-show="viewerOpen"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="portal-backdrop absolute inset-0 bg-[#1a0800]/75" x-on:click="closeViewer()"></div>

        <div class="relative flex max-h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-[1.75rem] border border-white/10 bg-[#1a0800] shadow-2xl shadow-black/40">
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
