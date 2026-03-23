@php
    $isDark = $theme === 'dark';
    $labelClass = $isDark ? 'text-gray-300' : 'text-gray-500';
    $inputClass = $isDark
        ? 'bg-white/10 border-white/15 text-white placeholder-white/30 focus:border-white/40'
        : 'bg-gray-50 border-gray-200 text-gray-800 placeholder-gray-400 focus:border-[#1B4F72]';
    $trackColor = $isDark ? '#F39C12' : '#1B4F72';
    $dividerClass = $isDark ? 'border-white/10' : 'border-gray-100';
    $valueClass = $isDark ? 'text-white' : 'text-gray-800';
    $subClass = $isDark ? 'text-gray-400' : 'text-gray-500';
    $highlightClass = $isDark ? 'text-[#F39C12]' : 'text-[#1B4F72]';
    $panelClass = $isDark ? 'bg-white/8' : 'bg-gray-50';
@endphp

<div
    x-data="loanCalc({{ $productsJson }})"
    x-init="init()"
    class="space-y-5"
>
    {{-- Product selector --}}
    <div class="relative">
        <label class="block text-xs font-medium {{ $labelClass }} mb-1.5 uppercase tracking-wider">Loan Product</label>

        <div class="relative" x-on:click.outside="productMenuOpen = false">
            <button
                type="button"
                x-on:click="productMenuOpen = !productMenuOpen"
                x-on:keydown.escape.prevent.stop="productMenuOpen = false"
                class="flex w-full items-center justify-between rounded-xl border px-3.5 py-3 text-left text-sm outline-none transition-colors {{ $inputClass }}"
                x-bind:aria-expanded="productMenuOpen"
            >
                <span class="truncate pr-3 {{ $valueClass }}" x-text="productLabel"></span>
                <svg
                    class="h-4 w-4 shrink-0 transition-transform {{ $subClass }}"
                    x-bind:class="productMenuOpen ? 'rotate-180' : ''"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div
                x-show="productMenuOpen"
                x-transition.origin.top
                x-on:keydown.escape.prevent.stop="productMenuOpen = false"
                class="absolute z-40 mt-2 w-full overflow-hidden rounded-2xl border {{ $isDark ? 'border-white/10 bg-[#0b1b28] shadow-2xl shadow-black/35' : 'border-gray-200 bg-white shadow-xl shadow-slate-200/60' }}"
                style="display: none;"
            >
                <div class="max-h-56 overflow-y-auto p-1.5">
                    <template x-for="(p, i) in products" :key="p.id">
                        <button
                            type="button"
                            x-on:click="selectProduct(i)"
                            class="flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left text-sm transition-colors"
                            x-bind:class="Number(productIdx) === Number(i)
                                ? '{{ $isDark ? 'bg-[#1B4F72]/70 text-white' : 'bg-[#1B4F72]/10 text-[#1B4F72]' }}'
                                : '{{ $isDark ? 'text-gray-200 hover:bg-white/8' : 'text-gray-700 hover:bg-slate-50' }}'"
                        >
                            <span class="truncate pr-3" x-text="productName(p)"></span>
                            <svg
                                x-show="Number(productIdx) === Number(i)"
                                class="h-4 w-4 shrink-0 {{ $isDark ? 'text-[#4EA8D9]' : 'text-[#1B4F72]' }}"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                style="display: none;"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Amount --}}
    <div>
        <div class="flex justify-between items-baseline mb-1.5">
            <label class="text-xs font-medium {{ $labelClass }} uppercase tracking-wider">Loan Amount</label>
            <span class="text-sm font-medium {{ $valueClass }}" x-text="'ZMW ' + fmt(amount)"></span>
        </div>
        <input
            type="range"
            x-model="amount"
            :min="curProduct.min_amount"
            :max="curProduct.max_amount"
            step="500"
            class="w-full h-1.5 rounded-full cursor-pointer transition-all"
            :style="'accent-color:{{ $trackColor }};' + trackFill(amount, curProduct.min_amount, curProduct.max_amount)"
        >
        <div class="flex justify-between text-[11px] {{ $subClass }} mt-1">
            <span x-text="'ZMW ' + fmt(curProduct.min_amount)"></span>
            <span x-text="'ZMW ' + fmt(curProduct.max_amount)"></span>
        </div>
    </div>

    {{-- Tenure --}}
    <div>
        <div class="flex justify-between items-baseline mb-1.5">
            <label class="text-xs font-medium {{ $labelClass }} uppercase tracking-wider">Repayment Period</label>
            <span class="text-sm font-medium {{ $valueClass }}" x-text="tenure + ' months'"></span>
        </div>
        <input
            type="range"
            x-model="tenure"
            :min="curProduct.min_tenure"
            :max="curProduct.max_tenure"
            step="1"
            class="w-full h-1.5 rounded-full cursor-pointer transition-all"
            :style="'accent-color:{{ $trackColor }};' + trackFill(tenure, curProduct.min_tenure, curProduct.max_tenure)"
        >
        <div class="flex justify-between text-[11px] {{ $subClass }} mt-1">
            <span x-text="curProduct.min_tenure + ' months'"></span>
            <span x-text="curProduct.max_tenure + ' months'"></span>
        </div>
    </div>

    {{-- Results --}}
    <div class="border-t {{ $dividerClass }} pt-5">
        <div class="text-center mb-4">
            <p class="text-[11px] font-medium {{ $subClass }} uppercase tracking-wider mb-1">Monthly Repayment</p>
            <p class="text-3xl font-semibold {{ $highlightClass }} tabular-nums" x-text="'ZMW ' + fmt2(monthly)"></p>
        </div>

        <div class="mb-1">
            <div class="flex h-2 rounded-full overflow-hidden {{ $panelClass }} mb-2">
                <div class="bg-[#16a34a] transition-all duration-500 ease-out" :style="'width:' + principalPct + '%'"></div>
                <div class="bg-[#F39C12] transition-all duration-500 ease-out" :style="'width:' + interestPct + '%'"></div>
            </div>
            <div class="flex justify-between text-[11px] {{ $subClass }}">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#16a34a] inline-block shrink-0"></span>
                    Principal <span class="tabular-nums" x-text="principalPct + '%'"></span>
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#F39C12] inline-block shrink-0"></span>
                    Interest <span class="tabular-nums" x-text="interestPct + '%'"></span>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2.5 mt-4">
            <div class="rounded-xl p-3 {{ $panelClass }} text-center">
                <p class="text-[11px] {{ $subClass }} mb-0.5">Total Interest</p>
                <p class="text-sm font-medium {{ $valueClass }} tabular-nums" x-text="'ZMW ' + fmt2(totalInterest)"></p>
            </div>
            <div class="rounded-xl p-3 {{ $panelClass }} text-center">
                <p class="text-[11px] {{ $subClass }} mb-0.5">Total Repayment</p>
                <p class="text-sm font-medium {{ $valueClass }} tabular-nums" x-text="'ZMW ' + fmt2(totalRepayment)"></p>
            </div>
        </div>

        <a
            :href="applyUrl"
            class="mt-4 flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl
                   bg-[#166534] text-white text-sm font-semibold
                   shadow-lg shadow-[#166534]/30 hover:bg-[#14532d] hover:-translate-y-0.5
                   active:scale-[.98] transition-all duration-200 select-none"
        >
            Apply at these terms
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <p class="text-[10px] {{ $subClass }} text-center mt-2.5 opacity-70">
            Interest calculated as a flat rate on principal. No hidden charges.
        </p>
    </div>
</div>

<script>
function loanCalc(products) {
    return {
        products,
        productMenuOpen: false,
        productIdx: 0,
        amount: 10000,
        tenure: 12,

        get curProduct() {
            return this.products[this.productIdx] ?? this.products[0];
        },

        get productLabel() {
            return this.productName(this.curProduct);
        },

        get monthly() {
            const r = this.curProduct.interest_rate / 100;
            const n = Number(this.tenure);
            const P = Number(this.amount);
            if (!r || !n || !P) return 0;
            return (P + P * r * n) / n;
        },

        get totalInterest() {
            return this.monthly * this.tenure - this.amount;
        },

        get totalRepayment() {
            return this.monthly * this.tenure;
        },

        get principalPct() {
            if (!this.totalRepayment) return 100;
            return Math.round((Number(this.amount) / this.totalRepayment) * 100);
        },

        get interestPct() {
            return 100 - this.principalPct;
        },

        get applyUrl() {
            const base = this.curProduct.apply_url || '';
            return base + '?amount=' + this.amount + '&tenure=' + this.tenure;
        },

        init() {
            this.clampSliders();
        },

        clampSliders() {
            const p = this.curProduct;
            this.amount = Math.min(Math.max(Number(this.amount), p.min_amount), p.max_amount);
            this.tenure = Math.min(Math.max(Number(this.tenure), p.min_tenure), p.max_tenure);
        },

        selectProduct(index) {
            this.productIdx = Number(index);
            this.productMenuOpen = false;
            this.clampSliders();
        },

        productName(product) {
            if (!product) return '';
            return product.name + ' - ' + product.interest_rate + '%/mo';
        },

        fmt(n) {
            return Number(n).toLocaleString('en-ZM', { maximumFractionDigits: 0 });
        },

        fmt2(n) {
            return Number(n).toLocaleString('en-ZM', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        trackFill(val, min, max) {
            const pct = ((val - min) / (max - min)) * 100;
            return `background: linear-gradient(to right, {{ $trackColor }} ${pct}%, rgba(156,163,175,0.3) ${pct}%);`;
        },
    };
}
</script>
