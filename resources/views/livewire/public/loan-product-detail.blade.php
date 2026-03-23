<div class="min-h-screen bg-gray-50">

    {{-- ── Hero banner ── --}}
    <div class="relative bg-[#0c2336] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1800&q=80"
             alt="" class="absolute inset-0 w-full h-full object-cover opacity-15" loading="eager" fetchpriority="high" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-r from-[#0c2336]/98 via-[#0c2336]/85 to-[#0c2336]/40"></div>

        <div class="relative max-w-4xl mx-auto px-5 py-16">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-200 transition-colors mb-6">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All products
            </a>
            <span class="inline-flex items-center gap-1.5 bg-white/10 text-white/80 text-xs font-medium px-3 py-1.5 rounded-full mb-4 backdrop-blur-sm border border-white/15">
                {{ $product->requires_collateral ? 'Asset-backed' : 'Salary-backed' }}
            </span>
            <h1 class="text-3xl md:text-4xl font-semibold text-white mb-3">{{ $product->name }}</h1>
            <p class="text-slate-300 font-light max-w-xl text-base leading-relaxed">{{ $product->description }}</p>
        </div>
    </div>

    {{-- ── Stats card floating over hero ── --}}
    <div class="max-w-4xl mx-auto px-5">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 -mt-6 relative z-10 overflow-hidden">
            <div class="grid grid-cols-2 md:grid-cols-3">
                @foreach([
                    ['Interest Rate', $product->interest_rate . '%',                           'per month'],
                    ['Min Amount',    'ZMW ' . number_format($product->min_amount, 0),          'minimum loan'],
                    ['Max Amount',    'ZMW ' . number_format($product->max_amount, 0),          'maximum loan'],
                    ['Min Tenure',    $product->min_tenure_months . ' months',                 'minimum period'],
                    ['Max Tenure',    $product->max_tenure_months . ' months',                 'maximum period'],
                    ['Collateral',    $product->requires_collateral ? 'Required' : 'None',     'security'],
                ] as $i => $stat)
                    <div class="px-6 py-7 text-center
                         {{ $i < 3 ? 'border-b border-gray-100' : '' }}
                         {{ ($i % 3) < 2 ? 'border-r border-gray-100' : '' }}">
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1.5">{{ $stat[0] }}</p>
                        <p class="text-xl font-semibold text-[#1B4F72] mb-0.5">{{ $stat[1] }}</p>
                        <p class="text-[11px] text-gray-400 font-light">{{ $stat[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Features + Calculator ── --}}
    <div class="max-w-4xl mx-auto px-5 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

            {{-- Benefits --}}
            <div>
                <p class="text-xs text-[#2E86C1] font-medium tracking-[0.18em] uppercase mb-2">Benefits</p>
                <h2 class="text-xl font-semibold text-gray-800 mb-5">What you get</h2>
                <div class="h-px bg-gradient-to-r from-[#1B4F72]/20 to-transparent mb-6"></div>
                <ul class="space-y-4">
                    @foreach([
                        'Flat-rate interest — no compound surprises',
                        'Funds disbursed within 48 hours of approval',
                        'Flexible tenure up to ' . $product->max_tenure_months . ' months',
                        'Dedicated support throughout your loan term',
                        $product->requires_collateral ? 'Higher loan limits with asset backing' : 'No collateral required',
                    ] as $benefit)
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-[#1B4F72]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm text-gray-600 font-light leading-relaxed">{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Calculator --}}
            <div class="bg-[#0c2336] rounded-3xl p-8 border border-slate-700/50">
                <p class="text-xs text-[#F39C12] font-medium tracking-[0.18em] uppercase mb-4">Quick Estimate</p>
                <livewire:public.loan-calculator theme="dark" />
            </div>
        </div>

        {{-- Thin decorative divider --}}
        <div class="flex items-center gap-4 my-10">
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
            <div class="flex gap-1.5">
                <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                <div class="w-1 h-1 rounded-full bg-gray-300"></div>
            </div>
            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
        </div>

        {{-- CTA --}}
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Ready to apply?</h3>
            <p class="text-sm text-gray-400 font-light mb-7">No pre-registration needed — your account is created automatically when you apply.</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('loan.apply', $product->slug) }}"
                   class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl text-sm font-medium bg-[#1B4F72] text-white hover:bg-[#154060] transition-all duration-200 shadow-lg shadow-blue-900/20">
                    Apply for {{ $product->name }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl text-sm font-medium text-gray-500 hover:text-gray-700 border border-gray-200 hover:bg-gray-50 transition-all duration-200">
                    View all products
                </a>
            </div>
        </div>
    </div>

</div>
