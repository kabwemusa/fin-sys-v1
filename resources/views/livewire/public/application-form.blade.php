@php
    $isCollateral = $loanProduct->requires_collateral;

    $stepLabels = $isCollateral
        ? ['Personal', 'Employment', 'Collateral', 'Banking', 'Loan Details', 'Documents', 'Review']
        : ['Personal', 'Employment', 'Banking', 'Loan Details', 'Documents', 'Review'];

    $stepMeta = [
        'Personal'     => ['story' => 'Tell us who you are',          'sub' => 'The first step to your financial goal',    'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        'Employment'   => ['story' => 'Show us your income',           'sub' => 'Help us verify your repayment capacity',   'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        'Collateral'   => ['story' => 'Your security pledge',          'sub' => 'Details of the asset backing your loan',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        'Banking'      => ['story' => 'Where your funds will land',    'sub' => 'We disburse directly to your account',     'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        'Loan Details' => ['story' => 'Design your loan',              'sub' => 'Choose the amount and tenure that fits',   'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
        'Documents'    => ['story' => 'Back it up',                    'sub' => 'Clear scans speed up your approval',       'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        'Review'       => ['story' => 'Last look before launch',       'sub' => 'Confirm everything looks right',           'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];

    $currentLabel = $stepLabels[$currentStep - 1];
    $meta         = $stepMeta[$currentLabel] ?? ['story' => $currentLabel, 'sub' => '', 'icon' => 'M9 5l7 7-7 7'];
    $progressPct  = round(($currentStep / $totalSteps) * 100);
    $documentChecklist = $loanProduct->documentChecklist();

    $dateOnly   = ['dateFormat' => 'Y-m-d', 'altInput' => true, 'altFormat' => 'F j, Y', 'enableTime' => false];
    $dobConfig  = array_merge($dateOnly, ['maxDate' => now()->subYears(18)->format('Y-m-d')]);
    $pastConfig = array_merge($dateOnly, ['maxDate' => 'today']);
@endphp

<div class="min-h-screen bg-[#071520] flex flex-col">

    {{-- ═══════════════════════════════════════════════════
         COMPACT PAGE HEADER — back · product · step progress
         ═══════════════════════════════════════════════════ --}}
    <div class="bg-[#071520] border-b border-white/8 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-5">

                {{-- Row 1: back · product badge · step fraction + mini progress --}}
                <div class="flex items-center gap-3 py-2.5 border-b border-white/5">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-300 transition-colors group shrink-0">
                        <svg class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                    <span class="w-px h-4 bg-white/10 shrink-0"></span>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#F39C12]/10 border border-[#F39C12]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F39C12] animate-pulse shrink-0"></span>
                        <span class="text-[#F39C12] text-xs font-medium tracking-[0.12em] uppercase">{{ $loanProduct->name }}</span>
                    </div>
                    <div class="ml-auto flex items-center gap-2.5 shrink-0">
                        <span class="text-xs text-slate-500 font-light hidden sm:inline">Step</span>
                        <span class="text-white text-xs font-semibold">{{ $currentStep }}</span>
                        <span class="text-slate-600 text-[10px]">/</span>
                        <span class="text-slate-500 text-xs font-light">{{ $totalSteps }}</span>
                        <div class="w-16 h-1 bg-white/8 rounded-full overflow-hidden">
                            <div class="h-full bg-linear-to-r from-[#1B4F72] to-[#4EA8D9] rounded-full transition-all duration-700"
                                 style="width:{{ $progressPct }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Horizontal stepper --}}
                <div class="py-2.5 overflow-x-auto">
                    <div class="relative flex items-center gap-0 min-w-max md:min-w-0 md:justify-between">

                        {{-- Track --}}
                        <div class="absolute top-3.25 left-3 right-3 h-px bg-white/8 z-0 hidden md:block"></div>
                        <div class="absolute top-3.25 left-3 h-px bg-linear-to-r from-[#1B4F72] to-[#4EA8D9] z-0 hidden md:block transition-all duration-700"
                             style="width: calc({{ max(0, ($currentStep - 1) / max(1, $totalSteps - 1) * 100) }}% - 1.5rem)"></div>

                        @foreach($stepLabels as $i => $label)
                            @php $n = $i + 1; $isDone = $currentStep > $n; $isActive = $currentStep === $n; @endphp
                            <div class="relative flex flex-col items-center gap-1 z-10 px-2.5 md:px-0 first:pl-0 last:pr-0">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-semibold transition-all duration-300 shrink-0
                                     {{ $isDone ? 'bg-[#4EA8D9] text-white shadow-md shadow-[#4EA8D9]/30'
                                                : ($isActive ? 'bg-white text-[#1B4F72] shadow-lg ring-4 ring-[#4EA8D9]/20'
                                                             : 'bg-white/6 text-white/25 border border-white/10') }}">
                                    @if($isDone)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $n }}
                                    @endif
                                </div>
                                <span class="text-[9px] font-medium whitespace-nowrap transition-colors duration-300
                                     {{ $isActive ? 'text-white' : ($isDone ? 'text-[#4EA8D9]/70' : 'text-white/20') }}">
                                    {{ $label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         MAIN FORM CARD
         ═══════════════════════════════════════════════════ --}}
    <div class="flex-1 max-w-5xl mx-auto w-full px-4 py-4 pb-10">

        <div class="bg-white rounded-3xl overflow-hidden shadow-2xl shadow-black/40">

            {{-- Animated gradient progress bar --}}
            <div class="h-1 bg-gray-100">
                <div class="h-full bg-linear-to-r from-[#1B4F72] to-[#4EA8D9] transition-all duration-700"
                     style="width:{{ $progressPct }}%"></div>
            </div>

            <div class="flex flex-col lg:flex-row">

                {{-- ── SIDEBAR ── --}}
                <div class="hidden lg:flex w-52 shrink-0 flex-col bg-[#071520]">

                    {{-- Top: Step icon + name --}}
                    <div class="p-5">
                        <div class="w-9 h-9 rounded-2xl bg-[#F39C12]/15 border border-[#F39C12]/25 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-[#F39C12]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $meta['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-[#F39C12] font-medium tracking-[0.18em] uppercase mb-1.5">
                            Step {{ $currentStep }} / {{ $totalSteps }}
                        </p>
                        <h3 class="text-base font-semibold text-white mb-1 leading-snug">{{ $meta['story'] }}</h3>
                        <p class="text-xs text-slate-400 font-light leading-relaxed">{{ $meta['sub'] }}</p>
                        <div class="mt-4 h-px bg-linear-to-r from-[#4EA8D9]/40 to-transparent"></div>
                    </div>

                    {{-- Loan summary --}}
                    <div class="mx-4 mb-5 bg-white/6 border border-white/10 rounded-2xl px-4 py-3">
                        <p class="text-[9px] text-slate-500 uppercase tracking-wider mb-3">Loan Summary</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-light">Product</span>
                                <span class="text-xs text-white font-medium">{{ $loanProduct->name }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-light">Rate</span>
                                <span class="text-xs text-[#4EA8D9] font-semibold">{{ $loanProduct->interest_rate }}% / mo</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-light">Range</span>
                                <span class="text-xs text-white font-medium">ZMW {{ number_format($loanProduct->min_amount/1000,0) }}K–{{ number_format($loanProduct->max_amount/1000,0) }}K</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-400 font-light">Tenure</span>
                                <span class="text-xs text-white font-medium">{{ $loanProduct->min_tenure_months }}–{{ $loanProduct->max_tenure_months }} months</span>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- ── FORM CONTENT AREA ── --}}
                <div class="flex-1 flex flex-col">
                    <div class="flex-1 p-5 lg:p-8">

                        {{-- Step header --}}
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 rounded-2xl bg-[#1B4F72]/8 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $meta['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-gray-800 mb-0.5">{{ $currentLabel }}</h2>
                                <p class="text-sm text-gray-400 font-light">{{ $meta['sub'] }}</p>
                                <div class="mt-3 h-px bg-linear-to-r from-gray-100 to-transparent"></div>
                            </div>
                        </div>

                        <x-mary-form wire:submit.prevent="submit" class="space-y-0">

                            {{-- ══ STEP 1: Personal ══ --}}
                            @if($currentStep === 1)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-1">
                                    <x-mary-input label="Full Name *"         wire:model="name"                type="text"  placeholder="John Banda"            icon="o-user" />
                                    <x-mary-input label="Email Address *"     wire:model="email"               type="email" placeholder="john@example.com"      icon="o-envelope" />
                                    <x-mary-input label="Phone Number *"      wire:model="phone"               type="text"  placeholder="+260 97XXXXXXX"        icon="o-phone" />
                                    <x-mary-input label="NRC Number *"        wire:model="nrc_number"          type="text"  placeholder="123456/78/9"           icon="o-identification" />
                                    <x-mary-datepicker label="Date of Birth *" wire:model="date_of_birth"      icon="o-calendar" :config="$dobConfig" placeholder="Select date" />
                                    <x-mary-select label="Gender *"           wire:model="gender"              icon="o-users"
                                        :options="[['id'=>'male','name'=>'Male'],['id'=>'female','name'=>'Female']]"
                                        option-value="id" option-label="name" placeholder="Select gender" />
                                    <x-mary-select label="Marital Status *"   wire:model="marital_status"      icon="o-heart"
                                        :options="[['id'=>'single','name'=>'Single'],['id'=>'married','name'=>'Married'],['id'=>'divorced','name'=>'Divorced'],['id'=>'widowed','name'=>'Widowed']]"
                                        option-value="id" option-label="name" placeholder="Select status" />
                                    <x-mary-input label="City *"              wire:model="city"                type="text"  placeholder="Lusaka"                icon="o-map-pin" />
                                    <div class="md:col-span-2">
                                        <x-mary-textarea label="Residential Address *" wire:model="residential_address" placeholder="House No. 12, Kabulonga Road, Lusaka..." rows="2" />
                                    </div>
                                    <x-mary-input label="Province *"          wire:model="province"            type="text"  placeholder="Lusaka Province"       icon="o-map" />
                                </div>

                            {{-- ══ STEP 2: Employment ══ --}}
                            @elseif($currentStep === 2)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-1">
                                    <x-mary-input label="Employer Name *"     wire:model="employer_name"       placeholder="Government of Zambia"    icon="o-building-office-2" />
                                    <x-mary-input label="Job Title *"         wire:model="job_title"           placeholder="Senior Accountant"       icon="o-briefcase" />
                                    <x-mary-datepicker label="Employment Start Date" wire:model="employment_date" icon="o-calendar" :config="$pastConfig" placeholder="Select date" />
                                    <x-mary-input label="Monthly Income (ZMW) *" wire:model="monthly_income"  type="number" prefix="ZMW" placeholder="15,000" />
                                    <div class="md:col-span-2">
                                        <x-mary-textarea label="Employer Address" wire:model="employer_address" placeholder="P.O. Box 12345, Lusaka" rows="2" />
                                    </div>
                                </div>
                                <div class="mt-5 flex items-start gap-3 bg-blue-50 rounded-2xl p-4">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-blue-700 font-light leading-relaxed">We verify your income to ensure the repayment is comfortable. Your employer will not be contacted without your consent.</p>
                                </div>

                            {{-- ══ STEP 3: Collateral (collateral products only) ══ --}}
                            @elseif($currentStep === 3 && $loanProduct->requires_collateral)
                                <div class="mb-5 flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-xs text-amber-700 font-light leading-relaxed">This loan is asset-backed. Provide details of the asset you are pledging as security. Documents will be requested in the next step.</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-1">
                                    <x-mary-select label="Collateral Type *"  wire:model="collateral_type"     icon="o-tag"
                                        :options="[['id'=>'vehicle','name'=>'Vehicle'],['id'=>'property','name'=>'Property'],['id'=>'equipment','name'=>'Equipment'],['id'=>'other','name'=>'Other']]"
                                        option-value="id" option-label="name" placeholder="Select type" />
                                    <x-mary-input label="Estimated Value (ZMW) *" wire:model="collateral_value" type="number" prefix="ZMW" placeholder="500,000" />
                                    <x-mary-input label="Registration Number"  wire:model="collateral_registration" placeholder="ABZ 1234 (optional)" icon="o-hashtag" />
                                    <div class="md:col-span-2">
                                        <x-mary-textarea label="Description *" wire:model="collateral_description" rows="3" placeholder="Asset type, condition, location, identifying details..." />
                                    </div>
                                </div>

                            {{-- ══ STEP 3/4: Banking ══ --}}
                            @elseif(($currentStep === 3 && !$loanProduct->requires_collateral) || ($currentStep === 4 && $loanProduct->requires_collateral))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-1">
                                    <x-mary-input label="Bank Name *"         wire:model="bank_name"           placeholder="Zanaco, Standard Chartered…" icon="o-building-library" />
                                    <x-mary-input label="Account Number *"    wire:model="bank_account_number" placeholder="000 000 000"               icon="o-credit-card" />
                                    <x-mary-input label="Branch"              wire:model="bank_branch"         placeholder="Cairo Road Branch"          icon="o-map-pin" />
                                </div>
                                <div class="mt-5 flex items-start gap-3 bg-blue-50 rounded-2xl p-4">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs text-blue-700 font-light leading-relaxed">The account must be in your name and active. Funds are typically received within 48 hours of approval.</p>
                                </div>

                            {{-- ══ STEP 4/5: Loan Details ══ --}}
                            @elseif(($currentStep === 4 && !$loanProduct->requires_collateral) || ($currentStep === 5 && $loanProduct->requires_collateral))
                                {{-- Product stats card --}}
                                <div class="mb-6 grid grid-cols-3 gap-3">
                                    <div class="bg-[#1B4F72]/5 rounded-2xl px-4 py-4 text-center border border-[#1B4F72]/10">
                                        <p class="text-2xl font-bold text-[#1B4F72]">{{ $loanProduct->interest_rate }}%</p>
                                        <p class="text-[10px] text-gray-400 font-light mt-0.5">per month</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-2xl px-4 py-4 text-center border border-gray-100">
                                        <p class="text-sm font-semibold text-gray-700 leading-tight">ZMW {{ number_format($loanProduct->min_amount/1000,0) }}K–{{ number_format($loanProduct->max_amount/1000,0) }}K</p>
                                        <p class="text-[10px] text-gray-400 font-light mt-0.5">loan range</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-2xl px-4 py-4 text-center border border-gray-100">
                                        <p class="text-sm font-semibold text-gray-700">{{ $loanProduct->min_tenure_months }}–{{ $loanProduct->max_tenure_months }} mo</p>
                                        <p class="text-[10px] text-gray-400 font-light mt-0.5">tenure</p>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <x-mary-input label="Loan Amount (ZMW) *" wire:model="amount_requested" type="number" prefix="ZMW" icon="o-banknotes"
                                        hint="Min: ZMW {{ number_format($loanProduct->min_amount, 0) }} — Max: ZMW {{ number_format($loanProduct->max_amount, 0) }}" />
                                    <x-mary-input label="Repayment Period (Months) *" wire:model="tenure_months" type="number" icon="o-calendar-days"
                                        hint="Min: {{ $loanProduct->min_tenure_months }} — Max: {{ $loanProduct->max_tenure_months }} months" />
                                    <x-mary-textarea label="Purpose of Loan" wire:model="purpose" rows="3"
                                        placeholder="e.g. home improvement, business capital, education fees..." />
                                </div>

                            {{-- ══ STEP 5/6: Documents ══ --}}
                            @elseif(($currentStep === 5 && !$loanProduct->requires_collateral) || ($currentStep === 6 && $loanProduct->requires_collateral))
                                <div class="mb-5 flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-xs text-amber-700 font-light leading-relaxed">Upload the documents listed below. Required items block submission, while recommended items help the review move faster. PDF, JPG, PNG — max 5 MB each.</p>
                                </div>
                                <div class="space-y-3">
                                    <div class="mb-2 grid gap-3 sm:grid-cols-2">
                                        @foreach($documentChecklist as $item)
                                            <div class="rounded-2xl border {{ $item['required'] ? 'border-[#1B4F72]/15 bg-[#1B4F72]/5' : 'border-gray-100 bg-gray-50' }} px-4 py-3">
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-sm font-medium {{ $item['required'] ? 'text-[#1B4F72]' : 'text-gray-700' }}">{{ $item['label'] }}</p>
                                                    <span class="text-[10px] uppercase tracking-wider font-semibold {{ $item['required'] ? 'text-[#1B4F72]' : 'text-gray-400' }}">
                                                        {{ $item['required'] ? 'Required' : 'Recommended' }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 font-light mt-1.5 leading-relaxed">{{ $item['hint'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                    <x-mary-file wire:model="document_nrc"               label="NRC Copy (Required)"             accept="image/*,.pdf" hint="Front and back of your National Registration Card" />
                                    <x-mary-file wire:model="document_payslip"           label="Latest Payslip"                  accept="image/*,.pdf" hint="Most recent salary payslip" />
                                    <x-mary-file wire:model="document_bank_statement"    label="3-Month Bank Statement"          accept="image/*,.pdf" />
                                    <x-mary-file wire:model="document_employment_letter" label="Employment Letter"               accept="image/*,.pdf" />
                                    @if($loanProduct->requires_collateral)
                                        <x-mary-file wire:model="document_collateral_proof" label="Collateral Proof (Required)" accept="image/*,.pdf" hint="Title deed, log book, or other proof of ownership." />
                                    @endif
                                    <x-mary-file wire:model="document_selfie"            label="Selfie Holding NRC"              accept="image/*" hint="Clear photo of your face with NRC visible" />
                                </div>

                            {{-- ══ STEP 6/7: Review & Submit ══ --}}
                            @else
                                <div class="space-y-4">

                                    {{-- Personal block --}}
                                    <div class="rounded-2xl border border-gray-100 overflow-hidden">
                                        <div class="bg-gray-50 px-5 py-3 flex items-center gap-2 border-b border-gray-100">
                                            <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Personal</span>
                                        </div>
                                        <div class="px-5 py-4 grid grid-cols-2 gap-y-3 text-sm">
                                            <span class="text-gray-400">Name</span>     <span class="font-medium text-gray-700">{{ $name }}</span>
                                            <span class="text-gray-400">Email</span>    <span class="font-medium text-gray-700">{{ $email }}</span>
                                            <span class="text-gray-400">Phone</span>    <span class="font-medium text-gray-700">{{ $phone }}</span>
                                            <span class="text-gray-400">NRC</span>      <span class="font-medium text-gray-700">{{ $nrc_number }}</span>
                                            <span class="text-gray-400">Location</span> <span class="font-medium text-gray-700">{{ $city }}, {{ $province }}</span>
                                        </div>
                                    </div>

                                    {{-- Thin separator --}}
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-px bg-linear-to-r from-transparent to-gray-200"></div>
                                        <div class="flex gap-1">
                                            <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                            <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                            <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                        </div>
                                        <div class="flex-1 h-px bg-linear-to-l from-transparent to-gray-200"></div>
                                    </div>

                                    {{-- Loan block --}}
                                    <div class="rounded-2xl border border-gray-100 overflow-hidden">
                                        <div class="bg-gray-50 px-5 py-3 flex items-center gap-2 border-b border-gray-100">
                                            <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Loan</span>
                                        </div>
                                        <div class="px-5 py-4 grid grid-cols-2 gap-y-3 text-sm">
                                            <span class="text-gray-400">Product</span>       <span class="font-medium text-gray-700">{{ $loanProduct->name }}</span>
                                            <span class="text-gray-400">Amount</span>        <span class="font-bold text-[#1B4F72] text-base">ZMW {{ number_format((float)$amount_requested, 2) }}</span>
                                            <span class="text-gray-400">Tenure</span>        <span class="font-medium text-gray-700">{{ $tenure_months }} months</span>
                                            <span class="text-gray-400">Interest Rate</span> <span class="font-medium text-gray-700">{{ $loanProduct->interest_rate }}% / month</span>
                                        </div>
                                    </div>

                                    {{-- Consent notice --}}
                                    <div class="flex items-start gap-3 bg-[#1B4F72]/5 border border-[#1B4F72]/10 rounded-2xl p-4">
                                        <svg class="w-4 h-4 text-[#1B4F72] mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <p class="text-xs text-[#1B4F72]/80 font-light leading-relaxed">By submitting, you confirm all information is accurate and true, and you consent to a credit assessment being performed.</p>
                                    </div>

                                    {{-- System error banner (transaction / file storage failure) --}}
                                    @error('submission')
                                        <div class="flex items-start gap-3 bg-red-50 border border-red-200 rounded-2xl p-4">
                                            <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs text-red-700 leading-relaxed font-light">{{ $message }}</p>
                                        </div>
                                    @enderror
                                </div>
                            @endif

                            {{-- ── NAVIGATION BAR ── --}}
                            <x-slot:actions>
                                <div class="w-full mt-8 pt-6 border-t border-gray-100">

                                    {{-- Trust micro-badges --}}
                                    <div class="flex items-center justify-center gap-5 mb-5">
                                        @foreach([['M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z','Encrypted'],['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Secure'],['M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','No credit hit']] as [$path, $label])
                                            <div class="flex items-center gap-1.5 text-[11px] text-gray-400 font-light">
                                                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/>
                                                </svg>
                                                {{ $label }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="flex items-center justify-between gap-4">
                                        {{-- Back button --}}
                                        @if($currentStep > 1)
                                            <button type="button" wire:click="previousStep"
                                                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-medium text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 transition-all duration-200 border border-gray-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                </svg>
                                                Back
                                            </button>
                                        @else
                                            <div></div>
                                        @endif

                                        {{-- Continue / Submit --}}
                                        @if($currentStep < $totalSteps)
                                            <button type="button" wire:click="nextStep"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-75 cursor-wait"
                                                wire:target="nextStep"
                                                class="group cursor-pointer inline-flex items-center gap-2.5 px-8 py-3 rounded-2xl text-sm font-semibold bg-[#1B4F72] text-white hover:bg-[#154060] transition-all duration-200 shadow-lg shadow-[#1B4F72]/25 hover:shadow-[#1B4F72]/40 hover:-translate-y-0.5 disabled:pointer-events-none">
                                                {{-- Idle label + arrow --}}
                                                <span wire:loading.remove wire:target="nextStep" class="inline-flex items-center gap-2.5">
                                                    Save &amp; Continue
                                                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </span>
                                                {{-- Loading spinner --}}
                                                <span wire:loading wire:target="nextStep" class="inline-flex items-center gap-2.5">
                                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                    </svg>
                                                    Saving…
                                                </span>
                                            </button>
                                        @else
                                            <button type="submit"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-75 !cursor-wait"
                                                class="group cursor-pointer inline-flex items-center gap-2.5 px-8 py-3 rounded-2xl text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-all duration-200 shadow-lg shadow-emerald-600/25 hover:shadow-emerald-600/40 hover:-translate-y-0.5 disabled:pointer-events-none">
                                                {{-- Idle --}}
                                                <span wire:loading.remove class="inline-flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Submit Application
                                                </span>
                                                {{-- Loading spinner --}}
                                                <span wire:loading class="inline-flex items-center gap-2.5">
                                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>
                                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                                    </svg>
                                                    Submitting…
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </x-slot:actions>

                        </x-mary-form>
                    </div>
                </div>
                {{-- end form area --}}

            </div>
        </div>
        {{-- end main card --}}

    </div>
    {{-- end container --}}

</div>
