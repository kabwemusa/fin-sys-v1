<div class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/20 to-gray-50 flex items-center justify-center px-4 py-16">

    <div class="w-full max-w-md">

        {{-- Success icon --}}
        <div class="text-center mb-8">
            <div class="relative inline-flex items-center justify-center mb-6">
                {{-- Outer ring --}}
                <div class="w-24 h-24 rounded-full bg-emerald-50 absolute animate-ping opacity-20"></div>
                <div class="w-24 h-24 rounded-full bg-emerald-100 absolute"></div>
                <div class="w-20 h-20 rounded-full bg-emerald-500 flex items-center justify-center relative shadow-xl shadow-emerald-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Application Submitted!</h1>
            <p class="text-sm text-gray-400 font-light max-w-xs mx-auto leading-relaxed">
                Your application has been received and is being reviewed by our team.
            </p>
        </div>

        {{-- Reference card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 overflow-hidden mb-4">

            {{-- Emerald top strip --}}
            <div class="h-1 bg-gradient-to-r from-emerald-400 to-teal-400"></div>

            <div class="p-7">
                {{-- Reference highlight --}}
                <div class="bg-gray-50 rounded-2xl px-5 py-4 text-center mb-5">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Reference Number</p>
                    <p class="text-xl font-semibold text-[#1B4F72] tracking-wide">{{ $application->reference }}</p>
                </div>

                {{-- Thin divider --}}
                <div class="h-px bg-gradient-to-r from-transparent via-gray-100 to-transparent mb-5"></div>

                {{-- Details list --}}
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Loan Product</span>
                        <span class="font-medium text-gray-700">{{ $application->loanProduct->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Amount Requested</span>
                        <span class="font-semibold text-[#1B4F72]">ZMW {{ number_format($application->amount_requested, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Submitted</span>
                        <span class="font-medium text-gray-700">{{ $application->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Status</span>
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-600 text-xs font-medium px-2.5 py-1 rounded-full border border-amber-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse inline-block"></span>
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if (session('mail_delivery_warning'))
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-2xl p-4 mb-6">
                <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z"/></svg>
                <div>
                    <p class="text-xs font-medium text-amber-700 mb-0.5">Email follow-up needed</p>
                    <p class="text-xs text-amber-700 font-light leading-relaxed">{{ session('mail_delivery_warning') }}</p>
                </div>
            </div>
        @else
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">
                <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <div>
                    @if (session('portal_access_mode') === 'existing_account')
                        <p class="text-xs font-medium text-blue-700 mb-0.5">Use your existing portal account</p>
                        <p class="text-xs text-blue-600 font-light leading-relaxed">Sign in with your current customer portal password to track this application.</p>
                    @else
                        <p class="text-xs font-medium text-blue-700 mb-0.5">Check your email</p>
                        <p class="text-xs text-blue-600 font-light leading-relaxed">Your portal sign-in details have been sent by email so you can track your application online.</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Thin decorative divider --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="flex-1 h-px bg-linear-to-r from-transparent to-gray-200"></div>
            <div class="flex gap-1">
                <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                <div class="w-1 h-1 rounded-full bg-gray-300"></div>
            </div>
            <div class="flex-1 h-px bg-linear-to-l from-transparent to-gray-200"></div>
        </div>

        {{-- Actions --}}
        <div class="space-y-3">
            <a href="{{ route('login') }}"
               class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-sm font-medium bg-[#1B4F72] text-white hover:bg-[#154060] transition-all duration-200 shadow-md shadow-blue-900/20">
                Sign In to Track Your Application
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('home') }}"
               class="flex items-center justify-center w-full py-3 rounded-2xl text-sm font-medium text-gray-400 hover:text-gray-600 transition-colors">
                Back to home
            </a>
        </div>

    </div>
</div>
