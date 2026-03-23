<div>

    {{-- ═══════════════════════════════════════════════════════
         §1  HERO — Cinematic story opener
         ═══════════════════════════════════════════════════════ --}}
    <section class="relative min-h-screen flex items-center overflow-hidden bg-[#071520]">

        {{-- Subtle dot-grid texture --}}
        <div class="absolute inset-0 pointer-events-none"
             style="background-image:radial-gradient(circle, rgba(78,168,217,.12) 1px, transparent 1px); background-size:44px 44px;"></div>

        {{-- Atmospheric glow blobs --}}
        <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] rounded-full bg-[#2E86C1]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/5 w-72 h-72 rounded-full bg-[#F39C12]/6 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-6xl mx-auto px-5 py-28 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- ── LEFT: Narrative copy ── --}}
            <div>
                {{-- Status pill --}}
                <div class="inline-flex items-center gap-2 mb-8 px-4 py-2 rounded-full bg-white/5 border border-white/10 hero-up">
                    <span class="w-2 h-2 rounded-full bg-[#F39C12] animate-pulse shrink-0"></span>
                    <span class="text-[#F39C12] text-xs font-medium tracking-[0.15em] uppercase">Applications Open · Fast Approval</span>
                </div>

                {{-- Main headline --}}
                <h1 class="text-[2.8rem] md:text-[3.8rem] lg:text-[4.2rem] font-semibold leading-[1.04] text-white mb-6 hero-up d1">
                    Your dreams<br>
                    <span class="hero-squiggle text-[#4EA8D9]">deserve funding</span><br>
                    today.
                </h1>

                <p class="text-slate-400 text-base md:text-lg leading-relaxed mb-9 max-w-md font-light hero-up d2">
                    One form. No prior registration. Submit your details, get reviewed within 48 hours,
                    and funds land directly in your account.
                </p>

                {{-- Product CTA buttons --}}
                <div class="flex flex-wrap gap-3 mb-10 hero-up d3">
                    @foreach($products as $product)
                        <a href="{{ route('loan.apply', $product->slug) }}"
                           class="group inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl text-sm font-medium transition-all duration-300
                                  {{ $loop->first
                                        ? 'bg-[#F39C12] text-white hover:bg-[#e08c10] shadow-xl shadow-[#F39C12]/30 hover:shadow-[#F39C12]/50 hover:-translate-y-0.5'
                                        : 'bg-white/8 text-white border border-white/15 hover:bg-white/14 hover:border-white/30 hover:-translate-y-0.5' }}">
                            {{ $product->name }}
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>

                {{-- Social proof row --}}
                <div class="flex items-center gap-3 hero-up d4">
                    <div class="flex -space-x-2.5">
                        @foreach([
                            'photo-1494790108377-be9c29b29330',
                            'photo-1507003211169-0a1dd7228f2d',
                            'photo-1500648767791-00dcc994a43e',
                            'photo-1534528741775-53994a69daeb',
                        ] as $photo)
                            <div class="w-9 h-9 rounded-full border-2 border-[#071520] overflow-hidden ring-1 ring-white/10 shrink-0">
                                <img src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=72&h=72&q=80"
                                     class="w-full h-full object-cover" loading="lazy" decoding="async" width="72" height="72" alt="">
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">5,000+ people funded</p>
                        <p class="text-slate-500 text-xs font-light">across Zambia</p>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: Stacked photo cluster with floating stat cards ── --}}
            <div class="relative hidden lg:block h-[540px] hero-up d3">

                {{-- Photo layer 3 — farthest back, tilted left --}}
                <div class="float-a absolute right-10 top-10 w-44 h-56 opacity-40" style="transform:rotate(-8deg);">
                    <div class="w-full h-full rounded-[2rem] overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=400&q=80"
                             alt="" class="w-full h-full object-cover scale-110" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-[#1B4F72]/50"></div>
                    </div>
                </div>

                {{-- Photo layer 2 — mid depth --}}
                <div class="float-b absolute right-24 top-2 w-56 opacity-70" style="transform:rotate(4deg); height:320px;">
                    <div class="w-full h-full rounded-[2.5rem] overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1573497620053-ea5300f94f21?auto=format&fit=crop&w=500&q=80"
                             alt="" class="w-full h-full object-cover" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-[#0c2336]/25"></div>
                    </div>
                </div>

                {{-- Photo layer 1 — front, nearly straight --}}
                <div class="float-c absolute right-2 top-16 w-64" style="transform:rotate(-1.5deg); height:380px;">
                    <div class="w-full h-full rounded-[2.5rem] overflow-hidden shadow-2xl shadow-black/50">
                        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=600&q=80"
                             alt="" class="w-full h-full object-cover" loading="eager" fetchpriority="high" decoding="async">
                        <div class="absolute inset-0 bg-linear-to-t from-[#071520]/70 via-[#071520]/10 to-transparent"></div>
                        <div class="absolute bottom-5 left-5 right-5">
                            <p class="text-white text-xs font-medium opacity-80">Your financial story</p>
                            <div class="flex gap-1 mt-2">
                                @foreach([85,95,70,90,100] as $w)
                                    <div class="flex-1 h-1 rounded-full bg-white/15 overflow-hidden">
                                        <div class="h-full rounded-full bg-[#4EA8D9]/80" style="width:{{ $w }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating stat card: Approval Rate --}}
                <div class="float-r absolute left-0 top-16 bg-white/96 backdrop-blur-xl rounded-2xl px-4 py-3.5 shadow-2xl shadow-black/20 hero-up d4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#1B4F72]/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-light">Approval Rate</p>
                            <p class="text-xl font-bold text-gray-800 leading-tight">98%</p>
                        </div>
                    </div>
                </div>

                {{-- Floating stat card: Turnaround --}}
                <div class="float-a absolute left-3 bottom-16 bg-[#1B4F72] rounded-2xl px-4 py-3.5 shadow-2xl shadow-[#1B4F72]/50 hero-up d5">
                    <p class="text-xs text-[#4EA8D9] font-light mb-0.5">Avg. Disbursement</p>
                    <p class="text-white font-semibold text-sm">Under 48 hrs</p>
                    <div class="flex gap-0.5 mt-2.5">
                        @foreach([70,90,100,85,95] as $w)
                            <div class="flex-1 h-1.5 rounded-full bg-white/15 overflow-hidden">
                                <div class="h-full rounded-full bg-[#4EA8D9]" style="width:{{ $w }}%"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Decorative rings --}}
                <div class="absolute right-0 bottom-8 w-36 h-36 rounded-full border border-[#2E86C1]/15 pointer-events-none"></div>
                <div class="absolute right-8 bottom-16 w-16 h-16 rounded-full border border-[#F39C12]/12 pointer-events-none"></div>
            </div>
        </div>

        {{-- Animated scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 hero-up d5">
            <div class="w-5 h-9 rounded-full border border-white/20 flex items-start justify-center pt-2">
                <div class="w-1 h-2.5 rounded-full bg-white/50 scroll-dot"></div>
            </div>
            <span class="text-[9px] text-white/25 tracking-[0.2em] uppercase">Scroll</span>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §2  MARQUEE STATS TICKER
         ═══════════════════════════════════════════════════════ --}}
    <div class="bg-white border-y border-gray-100/80 py-4 overflow-hidden cursor-default">
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
                    <span class="w-1.5 h-1.5 rounded-full bg-[#F39C12] shrink-0"></span>
                    <span class="text-base font-semibold text-[#1B4F72]">{{ $stat[0] }}</span>
                    <span class="text-sm text-gray-400 font-light">{{ $stat[1] }}</span>
                </div>
            @endforeach
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════
         §3  PRODUCTS — Stacked card deck
         ═══════════════════════════════════════════════════════ --}}
    <section id="products" class="bg-gray-50 py-24 px-5 relative overflow-hidden">

        {{-- Decorative blob --}}
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-[#2E86C1]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto relative">

            <div class="text-center mb-16 reveal">
                <span class="inline-block text-[#2E86C1] text-xs font-medium tracking-[0.2em] uppercase mb-3 px-4 py-1.5 bg-blue-50 rounded-full border border-blue-100">
                    Loan Products
                </span>
                <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mt-4 leading-tight">
                    Choose your path to<br>
                    <span class="text-[#1B4F72]">financial freedom</span>
                </h2>
                <p class="text-gray-400 mt-3 font-light max-w-sm mx-auto text-sm">Simple, transparent products built around your life.</p>
            </div>

            {{-- Stacked product cards --}}
            <div class="space-y-0">
                @foreach($products as $i => $product)
                    <div class="relative reveal rd{{ $i+1 }} {{ $i > 0 ? '-mt-3' : '' }}">

                        {{-- "Deck peek" strips (only for non-last cards) --}}
                        @if(!$loop->last)
                            <div class="absolute -bottom-2 left-5 right-5 h-5 bg-gray-200/70 rounded-b-3xl -z-10"></div>
                            <div class="absolute -bottom-4 left-10 right-10 h-5 bg-gray-300/40 rounded-b-3xl -z-20"></div>
                        @endif

                        <div class="group relative bg-white rounded-3xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-gray-200/80 stacked-card">

                            {{-- Color accent top bar --}}
                            <div class="h-1.5 w-full {{ $loop->first ? 'bg-linear-to-r from-[#1B4F72] to-[#4EA8D9]' : 'bg-linear-to-r from-[#F39C12] to-[#e67e22]' }}"></div>

                            <div class="p-8 md:p-10">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 items-center">

                                    {{-- Column 1: Product identity --}}
                                    <div>
                                        <div class="flex items-center gap-3 mb-5">
                                            <div class="w-12 h-12 rounded-2xl {{ $loop->first ? 'bg-[#1B4F72]' : 'bg-[#F39C12]' }} flex items-center justify-center shrink-0 shadow-lg {{ $loop->first ? 'shadow-[#1B4F72]/30' : 'shadow-[#F39C12]/30' }}">
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
                                                <h3 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h3>
                                                <span class="text-xs {{ $loop->first ? 'text-[#1B4F72] bg-blue-50' : 'text-[#c07a0a] bg-amber-50' }} px-2.5 py-0.5 rounded-full font-medium">
                                                    {{ $product->requires_collateral ? 'Asset-backed' : 'Salary-backed' }}
                                                </span>
                                            </div>
                                        </div>
                                        <p class="text-gray-500 text-sm leading-relaxed font-light">{{ $product->description }}</p>
                                    </div>

                                    {{-- Column 2: Key numbers --}}
                                    <div>
                                        <div class="grid grid-cols-3 gap-3 text-center">
                                            <div class="py-3 px-2 rounded-2xl bg-gray-50">
                                                <p class="text-2xl font-bold {{ $loop->first ? 'text-[#1B4F72]' : 'text-[#F39C12]' }}">{{ $product->interest_rate }}%</p>
                                                <p class="text-[11px] text-gray-400 font-light mt-0.5 leading-tight">per month</p>
                                            </div>
                                            <div class="py-3 px-2 rounded-2xl bg-gray-50">
                                                <p class="text-sm font-semibold text-gray-700 leading-tight">ZMW {{ number_format($product->min_amount/1000,0) }}K–{{ number_format($product->max_amount/1000,0) }}K</p>
                                                <p class="text-[11px] text-gray-400 font-light mt-0.5">amount</p>
                                            </div>
                                            <div class="py-3 px-2 rounded-2xl bg-gray-50">
                                                <p class="text-sm font-semibold text-gray-700">{{ $product->min_tenure_months }}–{{ $product->max_tenure_months }}mo</p>
                                                <p class="text-[11px] text-gray-400 font-light mt-0.5">tenure</p>
                                            </div>
                                        </div>

                                        {{-- Range bar --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex justify-between mb-1.5">
                                                <span class="text-[11px] text-gray-400 font-light">Min</span>
                                                <span class="text-[11px] text-gray-500 font-medium">Max ZMW {{ number_format($product->max_amount/1000,0) }}K</span>
                                            </div>
                                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $loop->first ? 'bg-linear-to-r from-[#1B4F72] to-[#4EA8D9]' : 'bg-linear-to-r from-[#F39C12] to-[#e67e22]' }}" style="width:78%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Column 3: CTA --}}
                                    <div class="flex flex-col gap-3">
                                        <a href="{{ route('loan.apply', $product->slug) }}"
                                           class="group/btn flex items-center justify-center gap-2 w-full py-4 rounded-2xl text-sm font-semibold transition-all duration-300
                                                  {{ $loop->first
                                                        ? 'bg-[#1B4F72] text-white hover:bg-[#154060] shadow-lg shadow-[#1B4F72]/25 hover:shadow-[#1B4F72]/40 hover:-translate-y-0.5'
                                                        : 'bg-gray-900 text-white hover:bg-gray-700 shadow-lg shadow-gray-900/15 hover:-translate-y-0.5' }}">
                                            Apply Now
                                            <svg class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                        <div class="flex items-center gap-2 text-xs text-gray-400 justify-center">
                                            <svg class="w-3.5 h-3.5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <section class="bg-[#071520] py-28 px-5 relative overflow-hidden">

        {{-- Background texture --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.025]"
             style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:36px 36px;"></div>
        <div class="absolute top-0 inset-x-0 h-px bg-linear-to-r from-transparent via-white/10 to-transparent"></div>
        <div class="absolute bottom-0 inset-x-0 h-px bg-linear-to-r from-transparent via-white/10 to-transparent"></div>
        <div class="absolute top-1/3 -right-20 w-80 h-80 rounded-full bg-[#2E86C1]/8 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 -left-20 w-64 h-64 rounded-full bg-[#F39C12]/5 blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto relative">

            <div class="text-center mb-20 reveal">
                <span class="inline-block text-[#F39C12] text-xs font-medium tracking-[0.2em] uppercase mb-3 px-4 py-1.5 bg-[#F39C12]/10 rounded-full border border-[#F39C12]/20">
                    The Journey
                </span>
                <h2 class="text-3xl md:text-4xl font-semibold text-white mt-4 leading-tight">
                    From idea to funded<br>
                    <span class="text-[#4EA8D9]">in 3 simple steps</span>
                </h2>
            </div>

            @php
                $steps = [
                    [
                        'num'   => '01',
                        'title' => 'Apply Online',
                        'body'  => 'Fill our intuitive multi-step form with your personal, employment and banking details. Upload supporting documents digitally — no branch visits, no queues.',
                        'img'   => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=700&q=80',
                        'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'cta'   => true,
                        'shape' => '60% 40% 55% 45% / 45% 55% 45% 55%',
                    ],
                    [
                        'num'   => '02',
                        'title' => 'Get Reviewed',
                        'body'  => 'Our team verifies your documents typically within 24 hours. We may request additional information through your secure portal — no unnecessary delays.',
                        'img'   => 'https://images.unsplash.com/photo-1551836022-deb4988cc6c0?auto=format&fit=crop&w=700&q=80',
                        'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        'cta'   => false,
                        'shape' => '45% 55% 40% 60% / 55% 45% 55% 45%',
                    ],
                    [
                        'num'   => '03',
                        'title' => 'Receive Funds',
                        'body'  => 'Once approved, funds are disbursed directly to your bank account — typically within 48 hours. Track every step live from your personal customer portal.',
                        'img'   => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=700&q=80',
                        'icon'  => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                        'cta'   => false,
                        'shape' => '50% 50% 45% 55% / 55% 45% 55% 45%',
                    ],
                ];
            @endphp

            <div class="space-y-20 md:space-y-28">
                @foreach($steps as $i => $step)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-20 items-center">

                        {{-- Content block — alternates sides --}}
                        <div class="{{ $i % 2 === 1 ? 'md:order-2' : 'md:order-1' }} reveal-{{ $i % 2 === 1 ? 'right' : 'left' }} rd{{ $i+1 }}">

                            {{-- Step number + icon row --}}
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-[#2E86C1]/15 border border-[#2E86C1]/25 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-[#4EA8D9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step['icon'] }}"/>
                                    </svg>
                                </div>
                                <span class="text-6xl font-black text-white/5 leading-none select-none tracking-tighter">{{ $step['num'] }}</span>
                            </div>

                            <h3 class="text-2xl md:text-3xl font-semibold text-white mb-3">{{ $step['title'] }}</h3>
                            <p class="text-slate-400 font-light leading-relaxed text-sm md:text-base">{{ $step['body'] }}</p>

                            @if($step['cta'])
                                <a href="{{ route('loan.apply', $products->first()?->slug) }}"
                                   class="inline-flex items-center gap-2 mt-7 text-sm text-[#4EA8D9] hover:text-white transition-colors group font-medium">
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
                                        <div class="w-2.5 h-2.5 rounded-full {{ $j === $i ? 'bg-[#F39C12] shadow-lg shadow-[#F39C12]/50' : ($j < $i ? 'bg-[#4EA8D9]/60' : 'bg-white/15') }} transition-all"></div>
                                        @if(!$loop->last)
                                            <div class="w-8 h-px {{ $j < $i ? 'bg-[#4EA8D9]/40' : 'bg-white/10' }}"></div>
                                        @endif
                                    </div>
                                @endforeach
                                <span class="text-xs text-slate-500 font-light ml-1">Step {{ $i+1 }} of 3</span>
                            </div>
                        </div>

                        {{-- Image block — organic blob shape --}}
                        <div class="{{ $i % 2 === 1 ? 'md:order-1' : 'md:order-2' }} reveal-{{ $i % 2 === 1 ? 'left' : 'right' }} rd{{ $i+1 }} relative">

                            {{-- Glow under image --}}
                            <div class="absolute inset-8 bg-[#2E86C1]/20 blur-2xl rounded-full pointer-events-none"></div>

                            {{-- Organic blob image --}}
                            <div class="relative overflow-hidden shadow-2xl shadow-black/50" style="height:300px; border-radius:{{ $step['shape'] }};">
                                <img src="{{ $step['img'] }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                <div class="absolute inset-0 bg-[#071520]/30"></div>
                            </div>

                            {{-- Step badge --}}
                            <div class="absolute {{ $i % 2 === 1 ? '-right-3 md:right-auto md:-left-3' : '-right-3' }} top-8 w-11 h-11 rounded-full bg-[#F39C12] text-white flex items-center justify-center text-sm font-bold shadow-xl shadow-[#F39C12]/40 select-none">
                                {{ $i + 1 }}
                            </div>
                        </div>

                    </div>

                    {{-- Connector between steps --}}
                    @if(!$loop->last)
                        <div class="flex items-center justify-center gap-4 -my-6 reveal">
                            <div class="flex-1 max-w-xs h-px bg-linear-to-r from-transparent to-white/10"></div>
                            <div class="flex gap-1.5">
                                <div class="w-1 h-1 rounded-full bg-white/15"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-[#4EA8D9]/40"></div>
                                <div class="w-1 h-1 rounded-full bg-white/15"></div>
                            </div>
                            <div class="flex-1 max-w-xs h-px bg-linear-to-l from-transparent to-white/10"></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §5  CALCULATOR — Clean bright section
         ═══════════════════════════════════════════════════════ --}}
    <section class="bg-white py-24 px-5 relative overflow-hidden">

        <div class="absolute inset-0 bg-linear-to-br from-slate-50 via-white to-blue-50/30 pointer-events-none"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 rounded-full bg-[#4EA8D9]/5 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: explainer --}}
            <div class="reveal-left">
                <span class="inline-block text-[#2E86C1] text-xs font-medium tracking-[0.2em] uppercase mb-4 px-4 py-1.5 bg-blue-50 rounded-full border border-blue-100">
                    Loan Calculator
                </span>
                <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-4 mt-3 leading-tight">
                    Know your numbers<br>
                    <span class="text-[#1B4F72]">before you commit</span>
                </h2>
                <p class="text-gray-500 font-light leading-relaxed mb-8 text-sm">
                    Adjust the sliders to model your ideal loan. No surprises — the rate you calculate is the rate you pay.
                </p>

                {{-- Feature checklist --}}
                <div class="space-y-3 mb-10">
                    @foreach([
                        'Instant monthly payment estimate',
                        'Fixed flat rate — no variable surprises',
                        'Flexible tenure up to 24 months',
                        'No commitment to use the calculator',
                    ] as $feat)
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full bg-[#1B4F72]/8 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600 font-light">{{ $feat }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Image teaser --}}
                <div class="relative rounded-2xl overflow-hidden h-36">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=700&q=80"
                         alt="" class="w-full h-full object-cover" loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-linear-to-r from-[#1B4F72]/60 to-[#1B4F72]/10"></div>
                    <div class="absolute inset-0 flex items-center px-6">
                        <p class="text-white font-semibold text-sm">Transparent. Fair. Simple.</p>
                    </div>
                </div>
            </div>

            {{-- Right: calculator component --}}
            <div class="reveal-right rd2">
                <div class="rounded-3xl p-8 bg-[#071622] border border-slate-700/50 shadow-2xl shadow-[#071622]/30">
                    <livewire:public.loan-calculator theme="dark" />
                </div>
            </div>

        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §6  WHY CHOOSE US — Feature grid on navy
         ═══════════════════════════════════════════════════════ --}}
    <section class="bg-[#1B4F72] py-24 px-5 relative overflow-hidden">

        {{-- Dot pattern --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.045]"
             style="background-image:radial-gradient(circle, #fff 1px, transparent 1px); background-size:38px 38px;"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#4EA8D9]/10 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2"></div>

        <div class="relative max-w-6xl mx-auto">

            <div class="text-center mb-16 reveal">
                <h2 class="text-3xl md:text-4xl font-semibold text-white leading-tight">
                    Built differently,<br>
                    <span class="text-[#4EA8D9]">for you</span>
                </h2>
                <p class="text-blue-200/50 mt-3 font-light text-sm">Everything you need. Nothing you don't.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['No Registration', 'Your account is created automatically when you apply. Zero pre-registration friction or paperwork.', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', '#4EA8D9'],
                    ['48hr Processing', 'Most applications are reviewed and decided within 24–48 hours of document submission.', 'M13 10V3L4 14h7v7l9-11h-7z', '#F39C12'],
                    ['Transparent Rates', 'Fixed flat-rate interest. No hidden fees, no penalty surprises on your statement.', 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', '#4EA8D9'],
                    ['Secure Portal', 'Track repayments, view statements, and monitor your loan status from your personal portal.', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', '#F39C12'],
                ] as $i => $feat)
                    <div class="group bg-white/5 hover:bg-white/10 border border-white/8 hover:border-white/18 rounded-3xl p-7 transition-all duration-300 cursor-default reveal rd{{ $i+1 }}">
                        <div class="w-11 h-11 rounded-2xl bg-white/8 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" style="color:{{ $feat[3] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $feat[2] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2 text-sm">{{ $feat[0] }}</h3>
                        <p class="text-sm text-blue-200/50 leading-relaxed font-light">{{ $feat[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════
         §7  CTA — Bold, dramatic closer
         ═══════════════════════════════════════════════════════ --}}
    <section class="relative bg-gray-950 py-32 px-5 overflow-hidden">

        {{-- Background image --}}
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1800&q=80"
                 alt="" class="w-full h-full object-cover opacity-[0.07]" loading="lazy" decoding="async">
        </div>

        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-linear-to-r from-gray-950/98 via-gray-950/90 to-gray-950/98"></div>

        {{-- Massive watermark text --}}
        <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none select-none">
            <span class="text-white font-black opacity-[0.025] whitespace-nowrap" style="font-size:16vw; line-height:1;">FUNDED</span>
        </div>

        {{-- Glow --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] rounded-full bg-[#1B4F72]/20 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto text-center reveal">

            <span class="inline-block text-[#F39C12] text-xs font-medium tracking-[0.2em] uppercase mb-7 px-4 py-1.5 bg-[#F39C12]/10 rounded-full border border-[#F39C12]/20">
                Ready when you are
            </span>

            <h2 class="text-4xl md:text-6xl lg:text-7xl font-semibold text-white mb-6 leading-[1.02]">
                Your next chapter<br>
                starts with <span class="text-[#4EA8D9]">one form.</span>
            </h2>

            <p class="text-gray-500 font-light mb-12 text-base max-w-lg mx-auto leading-relaxed">
                No pre-registration required. No branch visits. Your account is created automatically — just apply.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                @foreach($products as $product)
                    <a href="{{ route('loan.apply', $product->slug) }}"
                       class="group inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl text-sm font-semibold transition-all duration-300
                              {{ $loop->first
                                    ? 'bg-[#F39C12] text-white hover:bg-[#e08c10] shadow-2xl shadow-[#F39C12]/20 hover:shadow-[#F39C12]/40 hover:-translate-y-1'
                                    : 'bg-white/8 text-white border border-white/15 hover:bg-white/14 hover:border-white/30 hover:-translate-y-1' }}">
                        Apply — {{ $product->name }}
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>

            <p class="text-gray-700 text-xs mt-8 font-light">
                All amounts in Zambian Kwacha (ZMW) &nbsp;·&nbsp; No hidden charges
            </p>
        </div>
    </section>


    {{-- ─── Footer ─────────────────────────────────────────── --}}
    <footer class="bg-gray-950 border-t border-white/5 text-gray-600 py-14 px-5">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-10">
                <div>
                    <p class="font-semibold text-white text-xl tracking-tight">
                        <span class="text-[#4EA8D9]">Loan</span>System
                    </p>
                    <p class="text-xs text-gray-600 mt-1 font-light">Financial freedom, simplified.</p>
                </div>
                <nav class="flex flex-wrap gap-6">
                    <a href="#products" class="text-sm hover:text-white transition-colors">Products</a>
                    <a href="{{ route('login') }}" class="text-sm hover:text-white transition-colors">Customer Portal</a>
                    <a href="{{ route('login') }}" class="text-sm hover:text-white transition-colors">Sign In</a>
                </nav>
            </div>
            <div class="border-t border-white/5 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-xs font-light">All amounts in Zambian Kwacha (ZMW)</p>
                <p class="text-xs font-light">© {{ date('Y') }} LoanSystem · All rights reserved</p>
            </div>
        </div>
    </footer>

</div>
