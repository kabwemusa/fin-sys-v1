@php
    $isDark = $theme === 'dark';
    $labelClass     = $isDark ? 'text-gray-300' : 'text-gray-500';
    $inputClass     = $isDark
        ? 'bg-white/10 border-white/15 text-white placeholder-white/30 focus:border-white/40'
        : 'bg-gray-50 border-gray-200 text-gray-800 placeholder-gray-400 focus:border-[#1B4F72]';
    $trackColor     = $isDark ? '#F39C12' : '#1B4F72';
    $dividerClass   = $isDark ? 'border-white/10' : 'border-gray-100';
    $valueClass     = $isDark ? 'text-white'       : 'text-gray-800';
    $subClass       = $isDark ? 'text-gray-400'    : 'text-gray-500';
    $highlightClass = $isDark ? 'text-[#F39C12]'   : 'text-[#1B4F72]';
    $panelClass     = $isDark ? 'bg-white/8'        : 'bg-gray-50';
@endphp

<div
    x-data="loanCalc({{ $productsJson }})"
    x-init="init()"
    class="space-y-5"
>
    {{-- Product selector --}}
    <div>
        <label class="block text-xs font-medium {{ $labelClass }} mb-1.5 uppercase tracking-wider">Loan Product</label>
        <select
            x-model="productIdx"
            x-on:change="clampSliders()"
            class="w-full rounded-xl border px-3.5 py-3 text-sm outline-none transition-colors {{ $inputClass }}"
        >
            <template x-for="(p, i) in products" :key="p.id">
                <option :value="i" x-text="p.name + ' — ' + p.interest_rate + '%/mo'"></option>
            </template>
        </select>
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
    <div class="border-t {{ $dividerClass }} pt-5 space-y-1">
        <div class="text-center mb-4">
            <p class="text-[11px] font-medium {{ $subClass }} uppercase tracking-wider mb-1">Monthly Repayment</p>
            <p class="text-3xl font-semibold {{ $highlightClass }} tabular-nums"
               x-text="'ZMW ' + fmt2(monthly)"></p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl p-3 {{ $panelClass }} text-center">
                <p class="text-[11px] {{ $subClass }} mb-0.5">Total Interest</p>
                <p class="text-sm font-medium {{ $valueClass }} tabular-nums"
                   x-text="'ZMW ' + fmt2(totalInterest)"></p>
            </div>
            <div class="rounded-xl p-3 {{ $panelClass }} text-center">
                <p class="text-[11px] {{ $subClass }} mb-0.5">Total Repayment</p>
                <p class="text-sm font-medium {{ $valueClass }} tabular-nums"
                   x-text="'ZMW ' + fmt2(totalRepayment)"></p>
            </div>
        </div>
    </div>

</div>

<script>
function loanCalc(products) {
    return {
        products,
        productIdx: 0,
        amount:  10000,
        tenure:  12,

        get curProduct() { return this.products[this.productIdx] ?? this.products[0]; },

        get monthly() {
            const r = this.curProduct.interest_rate / 100;
            const n = Number(this.tenure);
            const P = Number(this.amount);
            if (!r || !n || !P) return 0;
            // flat interest (simple)
            return (P + P * r * n) / n;
        },

        get totalInterest()  { return this.monthly * this.tenure - this.amount; },
        get totalRepayment() { return this.monthly * this.tenure; },

        init() {
            // pick product closest to default amount
            this.clampSliders();
        },

        clampSliders() {
            const p = this.curProduct;
            this.amount = Math.min(Math.max(Number(this.amount), p.min_amount), p.max_amount);
            this.tenure = Math.min(Math.max(Number(this.tenure), p.min_tenure), p.max_tenure);
        },

        // Format with commas, 0 decimals
        fmt(n) {
            return Number(n).toLocaleString('en-ZM', { maximumFractionDigits: 0 });
        },
        // Format with 2 decimals
        fmt2(n) {
            return Number(n).toLocaleString('en-ZM', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        // CSS background gradient for filled track
        trackFill(val, min, max) {
            const pct = ((val - min) / (max - min)) * 100;
            return `background: linear-gradient(to right, {{ $trackColor }} ${pct}%, rgba(156,163,175,0.3) ${pct}%);`;
        },
    };
}
</script>
