<div>

    {{-- ═══════════════════════════════════════════════════════
         §1  HERO — Calculator + floating organic blob photos
         ═══════════════════════════════════════════════════════ --}}
    <section id="hero" class="relative overflow-hidden bg-[#FEF9E1] dark:bg-[#1a0800]">

        <div class="absolute inset-0 pointer-events-none"
             style="background-image:radial-gradient(circle, rgba(249,115,22,.10) 1px, transparent 1px); background-size:44px 44px;"></div>

        <div class="absolute left-[16%] top-1/4 h-[28rem] w-[28rem] rounded-full bg-[#E98C00]/8 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-[12%] h-72 w-72 rounded-full bg-[#E98C00]/6 blur-3xl pointer-events-none"></div>
        <div class="absolute right-[28%] top-1/3 h-80 w-80 rounded-full bg-[#E98C00]/8 blur-3xl pointer-events-none"></div>

        @php
            $primaryApplyUrl = $products->first() ? route('loan.apply', $products->first()->slug) : '#products';
        @endphp

        <div class="relative z-10 mx-auto flex min-h-[calc(100svh-4rem)] max-w-6xl items-center px-4 py-8 sm:px-5 sm:py-10 lg:py-14">
            <div class="grid w-full items-center gap-10 xl:grid-cols-[minmax(0,1fr)_minmax(0,520px)] xl:gap-12">

                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#E98C00]/30 bg-[#E98C00]/10 dark:border-white/10 dark:bg-white/5 px-4 py-2 hero-up">
                        <span class="h-2 w-2 shrink-0 rounded-full bg-[#E98C00] animate-pulse"></span>
                        <span class="text-xs font-medium uppercase tracking-[0.15em] text-[#E98C00]">Applications Open · Fast Approval</span>
                    </div>

                    <h1 class="mt-6 max-w-[15ch] text-[2.3rem] font-semibold leading-[0.98] text-[#1a0800] dark:text-white hero-up d1 sm:text-[3.15rem] md:text-[4rem]">
                        <span class="block">You can</span>
                        <span class="hero-squiggle block text-[#E98C00]">apply for a loan</span>
                        <span class="block">today.</span>
                    </h1>

                    <p class="mt-6 max-w-lg text-base font-light leading-8 text-slate-600 dark:text-slate-400 hero-up d2">
                        One application. No prior registration. Clear terms, fast review, and direct disbursement into your account once approved.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3 hero-up d2">
                        @foreach([
                            ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'Verified lender'],
                            ['M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'Zero hidden fees'],
                            ['M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', '24-48 hour review'],
                        ] as [$icon, $label])
                            <div class="inline-flex items-center gap-2 rounded-2xl border border-[#1a0800]/10 bg-[#1a0800]/5 dark:border-white/10 dark:bg-white/5 px-3.5 py-2.5">
                                <svg class="h-4 w-4 shrink-0 text-[#E98C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                                </svg>
                                <span class="text-xs font-light text-slate-600 dark:text-slate-300">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-7 flex flex-wrap items-center gap-3 hero-up d3">
                        <a href="{{ $primaryApplyUrl }}"
                           class="inline-flex items-center gap-2 rounded-2xl bg-[#E98C00] px-5 py-3 text-sm font-semibold text-white shadow-xl shadow-[#E98C00]/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#C97A00]">
                            Start Application
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="#products"
                           class="inline-flex items-center gap-2 rounded-2xl border border-[#1a0800]/20 bg-[#1a0800]/5 dark:border-white/10 dark:bg-white/5 px-5 py-3 text-sm font-medium text-[#1a0800] dark:text-white transition-colors hover:bg-[#1a0800]/10 dark:hover:bg-white/10">
                            Browse products
                        </a>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3 hero-up d3">
                        <div class="flex -space-x-2.5">
                            @foreach([
                                'photo-1494790108377-be9c29b29330',
                                'photo-1507003211169-0a1dd7228f2d',
                                'photo-1500648767791-00dcc994a43e',
                                'photo-1534528741775-53994a69daeb',
                            ] as $photo)
                                <div class="h-10 w-10 shrink-0 overflow-hidden rounded-full border-2 border-[#FEF9E1] dark:border-[#1a0800] ring-1 ring-[#1a0800]/10 dark:ring-white/10">
                                    <img src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=72&h=72&q=80"
                                         class="h-full w-full object-cover" loading="lazy" decoding="async" width="72" height="72" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#1a0800] dark:text-white">5,000+ people funded</p>
                            <p class="text-xs font-light text-slate-500">Trusted across Zambia</p>
                        </div>
                    </div>

                    {{-- <div class="mt-8 grid gap-4 md:grid-cols-3 hero-up d4">
                        @foreach([
                            ['48 hrs', 'Typical review', 'Fast enough to act without guessing what comes next.'],
                            ['No account setup', 'Before applying', 'We create your profile automatically as part of the process.'],
                            ['Clear product options', 'Salary and asset backed', 'Choose a structure that fits your income and repayment style.'],
                        ] as [$value, $label, $body])
                            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-4">
                                <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500">{{ $label }}</p>
                                <p class="mt-2 text-2xl font-semibold text-white">{{ $value }}</p>
                                <p class="mt-2 text-sm font-light leading-6 text-slate-400">{{ $body }}</p>
                            </div>
                        @endforeach
                    </div> --}}
                </div>

                <div class="w-full max-w-[520px] xl:justify-self-end hero-up d2">
                    {{-- <div class="mb-4 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/70 bg-white/96 px-4 py-3 shadow-2xl shadow-black/15">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#E98C00]/10">
                                    <svg class="h-5 w-5 text-[#E98C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-medium text-gray-400">Approval Rate</p>
                                    <p class="text-2xl font-bold leading-none text-gray-800">98%</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500">Application flow</p>
                            <p class="mt-2 text-base font-semibold text-white">Simple, digital, trackable</p>
                            <p class="mt-1 text-sm font-light leading-6 text-slate-400">Review products, estimate payments, then apply with confidence.</p>
                        </div>
                    </div> --}}

                    <div class="rounded-[2rem] border border-gray-200 dark:border-slate-700/40 bg-white dark:bg-[#1a0800] p-6 shadow-2xl shadow-gray-200 dark:shadow-black/60 lg:p-8 hero-up d3">
                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <p class="mb-1 text-[10px] font-medium uppercase tracking-[0.18em] text-[#E98C00]">Loan Calculator</p>
                                <p class="text-2xl font-semibold leading-tight text-[#1a0800] dark:text-white sm:text-[1.9rem]">Know your numbers first</p>
                            </div>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-[#E98C00]/30 bg-[#E98C00]/20">
                                <svg class="h-5 w-5 text-[#E98C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>

                        <livewire:public.loan-calculator theme="light" />
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §2  MARQUEE STATS TICKER
         ═══════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-[#150700] border-y border-gray-100/80 dark:border-white/5 py-4 overflow-hidden cursor-default">
        <div class="marquee-track flex whitespace-nowrap select-none">
            @php
                $ticker = [
                    ['5,000+', 'Loans Disbursed'],
                    ['ZMW 50M+', 'Total Funded'],
                    ['98%', 'Approval Rate'],
                    ['< 48 hrs', 'Turnaround'],
                    ['Zero', 'Hidden Fees'],
                    ['100%', 'Digital Process'],
                    ['5,000+', 'Loans Disbursed'],
                    ['ZMW 50M+', 'Total Funded'],
                    ['98%', 'Approval Rate'],
                    ['< 48 hrs', 'Turnaround'],
                    ['Zero', 'Hidden Fees'],
                    ['100%', 'Digital Process'],
                ];
            @endphp
            @foreach($ticker as $stat)
                <div class="inline-flex items-center gap-3 px-9">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#E98C00] shrink-0"></span>
                    <span class="text-base font-semibold text-[#E98C00]">{{ $stat[0] }}</span>
                    <span class="text-sm text-gray-400 font-light">{{ $stat[1] }}</span>
                </div>
            @endforeach
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════
         §3  PRODUCTS — Stacked card deck
         ═══════════════════════════════════════════════════════ --}}
    <section id="products" class="bg-[#FEF9E1] dark:bg-[#150700] py-14 md:py-24 px-5 relative overflow-hidden">

        {{-- Decorative blob --}}
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-[#E98C00]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto relative">

            <div class="text-center mb-16 reveal">
                <span class="inline-block text-[#E98C00] text-xs font-medium tracking-[0.2em] uppercase mb-3 px-4 py-1.5 bg-[#FEF9E1] rounded-full border border-[#E98C00]">
                    Loan Products
                </span>
                <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 dark:text-white mt-4 leading-tight">
                    Choose your path to<br>
                    <span class="text-[#E98C00]">financial freedom</span>
                </h2>
                <p class="text-gray-600 dark:text-slate-400 mt-3 font-light max-w-sm mx-auto text-sm">Simple, transparent products built around your life.</p>
            </div>

            {{-- Stacked product cards --}}
            <div class="space-y-0">
                @foreach($products as $i => $product)
                    <div class="relative reveal rd{{ $i+1 }} {{ $i > 0 ? '-mt-3' : '' }}">

                        {{-- "Deck peek" strips (only for non-last cards) --}}
                        @if(!$loop->last)
                            <div class="absolute -bottom-2 left-5 right-5 h-5 bg-[#E98C00]/20 rounded-b-3xl -z-10"></div>
                            <div class="absolute -bottom-4 left-10 right-10 h-5 bg-[#E98C00]/10 rounded-b-3xl -z-20"></div>
                        @endif

                        <div class="group relative bg-white dark:bg-[#1a0800] rounded-3xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-gray-200/80 dark:hover:shadow-black/50 stacked-card">

                            {{-- Color accent top bar --}}
                            <div class="h-1.5 w-full {{ $loop->first ? 'bg-linear-to-r from-[#E98C00] to-[#E98C00]' : 'bg-linear-to-r from-[#E98C00] to-[#E98C00]' }}"></div>

                            <div class="p-8 md:p-10">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 items-center">

                                    {{-- Column 1: Product identity --}}
                                    <div>
                                        <div class="flex items-center gap-3 mb-5">
                                            <div class="w-12 h-12 rounded-2xl {{ $loop->first ? 'bg-[#E98C00]' : 'bg-[#E98C00]' }} flex items-center justify-center shrink-0 shadow-lg {{ $loop->first ? 'shadow-[#E98C00]/30' : 'shadow-[#E98C00]/30' }}">
                                                @if($loop->first)
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $product->name }}</h3>
                                                <span class="text-xs {{ $loop->first ? 'text-[#E98C00] bg-[#FEF9E1]' : 'text-[#C97A00] bg-[#FEF9E1]' }} px-2.5 py-0.5 rounded-full font-medium">
                                                    {{ $product->requires_collateral ? 'Asset-backed' : 'Salary-backed' }}
                                                </span>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 dark:text-slate-400 text-sm leading-relaxed font-light">{{ $product->description }}</p>
                                    </div>

                                    {{-- Column 2: Key numbers --}}
                                    <div>
                                        <div class="grid grid-cols-3 gap-3 text-center">
                                            <div class="py-3 px-2 rounded-2xl bg-[#FEF9E1]">
                                                <p class="text-2xl font-bold {{ $loop->first ? 'text-[#E98C00]' : 'text-[#E98C00]' }}">{{ $product->interest_rate }}%</p>
                                                <p class="text-[11px] text-gray-600 font-light mt-0.5 leading-tight">per month</p>
                                            </div>
                                            <div class="py-3 px-2 rounded-2xl bg-[#FEF9E1]">
                                                <p class="text-sm font-semibold text-gray-800 leading-tight">ZMW {{ number_format($product->min_amount/1000,0) }}K–{{ number_format($product->max_amount/1000,0) }}K</p>
                                                <p class="text-[11px] text-gray-600 font-light mt-0.5">amount</p>
                                            </div>
                                            <div class="py-3 px-2 rounded-2xl bg-[#FEF9E1]">
                                                <p class="text-sm font-semibold text-gray-800">{{ $product->min_tenure_months }}–{{ $product->max_tenure_months }}mo</p>
                                                <p class="text-[11px] text-gray-600 font-light mt-0.5">tenure</p>
                                            </div>
                                        </div>

                                        {{-- Range bar --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex justify-between mb-1.5">
                                                <span class="text-[11px] text-gray-600 font-light">Min</span>
                                                <span class="text-[11px] text-gray-700 font-medium">Max ZMW {{ number_format($product->max_amount/1000,0) }}K</span>
                                            </div>
                                            <div class="h-1.5 bg-[#FEF9E1] rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $loop->first ? 'bg-linear-to-r from-[#E98C00] to-[#E98C00]' : 'bg-linear-to-r from-[#E98C00] to-[#E98C00]' }}" style="width:78%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Column 3: CTA --}}
                                    <div class="flex flex-col gap-3">
                                        <a href="{{ route('loan.apply', $product->slug) }}"
                                           class="group/btn flex items-center justify-center gap-2 w-full py-4 rounded-2xl text-sm font-semibold transition-all duration-300
                                                  {{ $loop->first
                                                        ? 'bg-[#E98C00] text-white hover:bg-[#C97A00] shadow-lg shadow-[#E98C00]/25 hover:shadow-[#E98C00]/40 hover:-translate-y-0.5'
                                                        : 'bg-[#1a0800] text-white hover:bg-[#C97A00] shadow-lg shadow-black/20 hover:-translate-y-0.5' }}">
                                            Apply Now
                                            <svg class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                        <div class="flex items-center gap-2 text-xs text-gray-600 justify-center">
                                            <svg class="w-3.5 h-3.5 text-[#E98C00] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            No registration required
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §4  HOW IT WORKS — Alternating story timeline
         ═══════════════════════════════════════════════════════ --}}
    <section class="bg-white dark:bg-[#1a0800] py-16 lg:py-28 px-5 relative overflow-hidden">

        {{-- Background texture --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.025]"
             style="background-image:radial-gradient(circle, #1a0800 1px, transparent 1px); background-size:36px 36px;"></div>
        <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-gray-200 dark:via-white/10 to-transparent"></div>
        <div class="absolute bottom-0 inset-x-0 h-px bg-linear-to-r from-transparent via-gray-200 dark:via-white/10 to-transparent"></div>
        <div class="absolute top-1/3 -right-20 w-80 h-80 rounded-full bg-[#E98C00]/8 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 -left-20 w-64 h-64 rounded-full bg-[#E98C00]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto relative">

            <div class="text-center mb-12 md:mb-20 reveal">
                <span class="inline-block text-[#E98C00] text-xs font-medium tracking-[0.2em] uppercase mb-3 px-4 py-1.5 bg-[#E98C00]/10 rounded-full border border-[#E98C00]/20">
                    The Journey
                </span>
                <h2 class="text-3xl md:text-4xl font-semibold text-[#1a0800] dark:text-white mt-4 leading-tight">
                    From idea to funded<br>
                    <span class="text-[#E98C00]">in 3 simple steps</span>
                </h2>
            </div>

            @php
                $steps = [
                    [
                        'num'   => '01',
                        'title' => 'Apply Online',
                        'body'  => 'Fill our intuitive multi-step form with your personal, employment and banking details. Upload supporting documents digitally — no branch visits, no queues.',
                        'img'   => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&crop=faces,center&w=700&q=80',
                        'pos'   => 'object-top',
                        'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'cta'   => true,
                        'shape' => '60% 40% 55% 45% / 45% 55% 45% 55%',
                    ],
                    [
                        'num'   => '02',
                        'title' => 'Get Reviewed',
                        'body'  => 'Our team verifies your documents typically within 24 hours. We may request additional information through your secure portal — no unnecessary delays.',
                        'img'   => 'https://images.unsplash.com/photo-1551836022-deb4988cc6c0?auto=format&fit=crop&crop=faces,center&w=700&q=80',
                        'pos'   => 'object-top',
                        'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        'cta'   => false,
                        'shape' => '45% 55% 40% 60% / 55% 45% 55% 45%',
                    ],
                    [
                        'num'   => '03',
                        'title' => 'Receive Funds',
                        'body'  => 'Once approved, funds are disbursed directly to your bank account — typically within 48 hours. Track every step live from your personal customer portal.',
                        'img'   => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=700&q=80',
                        'pos'   => 'object-center',
                        'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                        'cta'   => false,
                        'shape' => '50% 50% 45% 55% / 55% 45% 55% 45%',
                    ],
                ];
            @endphp

            <div class="space-y-14 md:space-y-28">
                @foreach($steps as $i => $step)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-20 items-center">

                        {{-- Content block — alternates sides --}}
                        <div class="{{ $i % 2 === 1 ? 'md:order-2' : 'md:order-1' }} reveal-{{ $i % 2 === 1 ? 'right' : 'left' }} rd{{ $i+1 }}">

                            {{-- Step number + icon row --}}
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-[#E98C00]/15 border border-[#E98C00]/25 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-[#E98C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/>
                                    </svg>
                                </div>
                                <span class="text-6xl font-black text-[#1a0800]/5 dark:text-white/5 leading-none select-none tracking-tighter">{{ $step['num'] }}</span>
                            </div>

                            <h3 class="text-2xl md:text-3xl font-semibold text-[#1a0800] dark:text-white mb-3">{{ $step['title'] }}</h3>
                            <p class="text-slate-600 dark:text-slate-400 font-light leading-relaxed text-sm md:text-base">{{ $step['body'] }}</p>

                            @if($step['cta'])
                                <a href="{{ route('loan.apply', $products->first()?->slug) }}"
                                   class="inline-flex items-center gap-2 mt-7 text-sm text-[#E98C00] hover:text-[#C97A00] dark:hover:text-white transition-colors group font-medium">
                                    Start your application
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Inline mini-timeline indicator --}}
                            <div class="flex items-center gap-3 mt-8">
                                @foreach($steps as $j => $_s)
                                    <div class="flex items-center gap-2">
                                        <div class="w-2.5 h-2.5 rounded-full {{ $j === $i ? 'bg-[#E98C00] shadow-lg shadow-[#E98C00]/50' : ($j < $i ? 'bg-[#E98C00]/60' : 'bg-[#1a0800]/15 dark:bg-white/15') }} transition-all"></div>
                                        @if(!$loop->last)
                                            <div class="w-8 h-px {{ $j < $i ? 'bg-[#E98C00]/40' : 'bg-[#1a0800]/10 dark:bg-white/10' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                                <span class="text-xs text-slate-500 dark:text-slate-500 font-light ml-1">Step {{ $i+1 }} of 3</span>
                            </div>
                        </div>

                        {{-- Image block — organic blob shape --}}
                        <div class="{{ $i % 2 === 1 ? 'md:order-1' : 'md:order-2' }} reveal-{{ $i % 2 === 1 ? 'left' : 'right' }} rd{{ $i+1 }} relative">

                            {{-- Glow under image --}}
                            <div class="absolute inset-8 bg-[#E98C00]/15 blur-2xl rounded-full pointer-events-none"></div>

                            {{-- Organic blob image --}}
                            <div class="relative overflow-hidden shadow-2xl shadow-black/50 h-52 md:h-75" style="border-radius:{{ $step['shape'] }};">
                                <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover {{ $step['pos'] ?? 'object-top' }}" loading="lazy" decoding="async">
                                <div class="absolute inset-0 bg-[#1a0800]/30"></div>
                            </div>

                            {{-- Step badge --}}
                            <div class="absolute {{ $i % 2 === 1 ? '-right-3 md:right-auto md:-left-3' : '-right-3' }} top-8 w-11 h-11 rounded-full bg-[#E98C00] text-white flex items-center justify-center text-sm font-bold shadow-xl shadow-[#E98C00]/40 select-none">
                                {{ $i + 1 }}
                            </div>
                        </div>

                    </div>

                    {{-- Connector between steps --}}
                    @if(!$loop->last)
                        <div class="flex items-center justify-center gap-4 -my-6 reveal">
                            <div class="flex-1 max-w-xs h-px bg-linear-to-r from-transparent to-[#1a0800]/10 dark:to-white/10"></div>
                            <div class="flex gap-1.5">
                                <div class="w-1 h-1 rounded-full bg-[#1a0800]/15 dark:bg-white/15"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-[#E98C00]/40"></div>
                                <div class="w-1 h-1 rounded-full bg-[#1a0800]/15 dark:bg-white/15"></div>
                            </div>
                            <div class="flex-1 max-w-xs h-px bg-linear-to-l from-transparent to-[#1a0800]/10 dark:to-white/10"></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>


    {{-- §5 Calculator removed — now embedded in the hero section --}}
    {{-- ═══════════════════════════════════════════════════════
         §5  WHY CHOOSE US — Feature grid on navy
         ═══════════════════════════════════════════════════════ --}}

    <section class="bg-[#E98C00] py-14 md:py-24 px-5 relative overflow-hidden">

        {{-- Dot pattern --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.045]"
             style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:38px 38px;"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#E98C00]/10 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2"></div>

        <div class="relative max-w-6xl mx-auto">

            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-semibold text-white leading-tight">
                    Built differently,<br>
                    <span class="text-[#FEF9E1]">for you</span>
                </h2>
                <p class="text-white/60 mt-3 font-light text-sm">Everything you need. Nothing you don't.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['No Registration', 'Your account is created automatically when you apply. Zero pre-registration friction or paperwork.', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['48hr Processing', 'Most applications are reviewed and decided within 24–48 hours of document submission.', 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['Transparent Rates', 'Fixed flat-rate interest. No hidden fees, no penalty surprises on your statement.', 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['Secure Portal', 'Track repayments, view statements, and monitor your loan status from your personal portal.', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                ] as $i => $feat)
                    <div class="group bg-white/10 hover:bg-white/18 border border-white/15 hover:border-white/30 rounded-3xl p-7 transition-all duration-300 cursor-default reveal rd{{ $i+1 }}">
                        <div class="w-11 h-11 rounded-2xl bg-[#FEF9E1]/15 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5 text-[#FEF9E1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $feat[2] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2 text-sm">{{ $feat[0] }}</h3>
                        <p class="text-sm text-white/70 leading-relaxed font-light">{{ $feat[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §7  CTA — Bold, dramatic closer
         ═══════════════════════════════════════════════════════ --}}
    <section class="relative bg-[#FEF9E1] dark:bg-[#1a0800] py-20 md:py-32 px-5 overflow-hidden">

        {{-- Background image --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1800&q=80"
                 alt="" class="w-full h-full object-cover opacity-[0.04] dark:opacity-[0.07]" loading="lazy" decoding="async">
        </div>

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-linear-to-r from-[#FEF9E1]/98 via-[#FEF9E1]/90 to-[#FEF9E1]/98 dark:from-[#1a0800]/98 dark:via-[#1a0800]/90 dark:to-[#1a0800]/98"></div>

        {{-- Massive watermark text --}}
        <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none select-none">
            <span class="text-[#1a0800] dark:text-white font-black opacity-[0.025] whitespace-nowrap" style="font-size:16vw; line-height:1;">FUNDED</span>
        </div>

        {{-- Glow --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] rounded-full bg-[#E98C00]/15 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto text-center reveal">

            <span class="inline-block text-[#E98C00] text-xs font-medium tracking-[0.2em] uppercase mb-7 px-4 py-1.5 bg-[#E98C00]/10 rounded-full border border-[#E98C00]/20">
                Ready when you are
            </span>

            <h2 class="text-4xl md:text-6xl lg:text-7xl font-semibold text-[#1a0800] dark:text-white mb-6 leading-[1.02]">
                Your next chapter<br>
                starts with <span class="text-[#E98C00]">one form.</span>
            </h2>

            <p class="text-[#1a0800]/60 dark:text-white/60 font-light mb-12 text-base max-w-lg mx-auto leading-relaxed">
                No pre-registration required. No branch visits. Your account is created automatically — just apply.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                @foreach($products as $product)
                    <a href="{{ route('loan.apply', $product->slug) }}"
                       class="group inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl text-sm font-semibold transition-all duration-300
                              {{ $loop->first
                                    ? 'bg-[#E98C00] text-white hover:bg-[#C97A00] shadow-2xl shadow-[#E98C00]/25 hover:shadow-[#E98C00]/40 hover:-translate-y-1'
                                    : 'bg-[#1a0800]/8 text-[#1a0800] dark:bg-white/8 dark:text-white border border-[#1a0800]/15 dark:border-white/15 hover:bg-[#1a0800]/14 dark:hover:bg-white/14 hover:border-[#1a0800]/30 dark:hover:border-white/30 hover:-translate-y-1' }}">
                        Apply — {{ $product->name }}
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>

            <p class="text-[#1a0800]/35 dark:text-white/35 text-xs mt-8 font-light">
                All amounts in Zambian Kwacha (ZMW) &nbsp;·&nbsp; No hidden charges
            </p>
        </div>
    </section>


    {{-- ─── Footer ─────────────────────────────────────────── --}}
    <footer class="bg-[#FEF9E1] dark:bg-[#1a0800] border-t border-[#1a0800]/8 dark:border-white/8 py-14 px-5">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-10">
                <div>
                    <img src="{{ asset('images/logo.png') }}" alt="Orange Fin" class="h-9 w-auto mb-1">
                    <p class="text-xs text-[#1a0800]/40 dark:text-white/40 mt-1 font-light">Financial freedom, simplified.</p>
                </div>
                <nav class="flex flex-wrap gap-6">
                    <a href="#products" class="text-sm text-[#1a0800]/50 dark:text-white/50 hover:text-[#1a0800] dark:hover:text-white transition-colors">Products</a>
                    <a href="{{ route('login') }}" class="text-sm text-[#1a0800]/50 dark:text-white/50 hover:text-[#1a0800] dark:hover:text-white transition-colors">Customer Portal</a>
                    <a href="{{ route('login') }}" class="text-sm text-[#1a0800]/50 dark:text-white/50 hover:text-[#1a0800] dark:hover:text-white transition-colors">Sign In</a>
                </nav>
            </div>
            <div class="border-t border-[#1a0800]/8 dark:border-white/8 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-xs text-[#1a0800]/35 dark:text-white/35 font-light">All amounts in Zambian Kwacha (ZMW)</p>
                <p class="text-xs text-[#1a0800]/35 dark:text-white/35 font-light">© {{ date('Y') }} Orange Fin · All rights reserved</p>
            </div>
        </div>
    </footer>

</div>
