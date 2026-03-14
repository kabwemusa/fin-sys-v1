<div class="min-h-screen flex bg-white">

    {{-- ── Left brand panel ── --}}
    <div class="hidden lg:flex w-5/12 xl:w-1/2 relative flex-col justify-between overflow-hidden bg-[#0c2336]">
        <img src="https://images.unsplash.com/photo-1551836022-deb4988cc6c0?auto=format&fit=crop&w=1200&q=80"
             alt="" class="absolute inset-0 w-full h-full object-cover opacity-15" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0c2336]/95 via-[#0c2336]/80 to-[#1B4F72]/60"></div>

        {{-- Top logo --}}
        <div class="relative p-10">
            <a href="{{ route('home') }}" class="text-white font-semibold text-xl tracking-tight">LoanSystem</a>
        </div>

        {{-- Centre copy --}}
        <div class="relative px-10 pb-8">
            <p class="text-[#F39C12] text-xs font-medium tracking-[0.2em] uppercase mb-4">Secure Portal</p>
            <h2 class="text-3xl font-semibold text-white leading-snug mb-4">
                Manage your loans<br>
                <span class="text-[#4EA8D9]">from one place.</span>
            </h2>
            <p class="text-slate-300 font-light text-sm leading-relaxed max-w-xs">
                Track repayments, view your schedule, upload documents, and stay on top of your financial journey.
            </p>

            {{-- Thin horizontal divider with dots --}}
            <div class="flex items-center gap-3 my-8">
                <div class="flex-1 h-px bg-white/15"></div>
                <div class="flex gap-1.5">
                    <div class="w-1 h-1 rounded-full bg-white/30"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-[#4EA8D9]/60"></div>
                    <div class="w-1 h-1 rounded-full bg-white/30"></div>
                </div>
                <div class="flex-1 h-px bg-white/15"></div>
            </div>

            {{-- Stats with thin vertical connector lines --}}
            <div class="flex items-center gap-0">
                <div class="text-center pr-8">
                    <p class="text-2xl font-semibold text-white">5K+</p>
                    <p class="text-xs text-slate-400 mt-0.5">Customers</p>
                </div>
                <div class="w-px h-10 bg-white/15 flex-shrink-0"></div>
                <div class="text-center px-8">
                    <p class="text-2xl font-semibold text-white">ZMW 50M</p>
                    <p class="text-xs text-slate-400 mt-0.5">Disbursed</p>
                </div>
                <div class="w-px h-10 bg-white/15 flex-shrink-0"></div>
                <div class="text-center pl-8">
                    <p class="text-2xl font-semibold text-white">98%</p>
                    <p class="text-xs text-slate-400 mt-0.5">Satisfaction</p>
                </div>
            </div>
        </div>

        {{-- Bottom decorative line --}}
        <div class="relative p-10 pt-0">
            <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
            <p class="text-xs text-slate-500 mt-4">All amounts in Zambian Kwacha (ZMW) &nbsp;·&nbsp; © {{ date('Y') }}</p>
        </div>
    </div>

    {{-- ── Right login panel ── --}}
    <div class="flex-1 flex items-center justify-center px-6 py-16 bg-gray-50">
        <div class="w-full max-w-sm">

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8 text-center">
                <a href="{{ route('home') }}" class="text-[#1B4F72] font-semibold text-xl">LoanSystem</a>
            </div>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-semibold text-gray-800 mb-1">Welcome back</h1>
                <p class="text-sm text-gray-400 font-light">Sign in to access your customer portal</p>
            </div>

            {{-- Error flash --}}
            @if(session('error'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-2xl p-4 mb-6">
                    <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-red-600 leading-relaxed">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Login form --}}
            <form wire:submit.prevent="login" class="space-y-4">

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Email or Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input wire:model="identifier" type="text" placeholder="you@example.com or +260 97XXXXXXX" autocomplete="username"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    </div>
                    @error('identifier') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input wire:model="password" type="password" placeholder="••••••••" required
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    </div>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Thin divider --}}
                <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>

                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#1B4F72] text-white rounded-xl text-sm font-medium
                           hover:bg-[#154060] transition-all duration-200 shadow-md shadow-blue-900/20">
                    <span wire:loading.remove>Sign In</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Signing in…
                    </span>
                </button>
            </form>

            {{-- Thin decorative divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-300 font-light">or</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <div class="text-center space-y-3">
                <p class="text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('home') }}#products" class="text-[#1B4F72] font-medium hover:underline">Apply for a loan</a>
                </p>
                <p class="text-xs text-gray-400 font-light">
                    Your account is created automatically when you submit a loan application.
                </p>
            </div>
        </div>
    </div>

</div>
