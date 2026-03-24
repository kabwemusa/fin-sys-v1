<div class="min-h-screen flex items-center justify-center bg-[#1a0800] px-4">
    <div class="w-full max-w-md">

        {{-- Logo / brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-[#E98C00] flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <img src="{{ asset('images/logo.png') }}" alt="Orange Fin" class="h-8 w-auto">
            </div>
            <h1 class="text-2xl font-semibold text-white">Set a new password</h1>
            <p class="text-slate-400 text-sm mt-1 font-light">Choose something strong and memorable</p>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl shadow-black/40 p-8">

            @if($done)
                {{-- Success state --}}
                <div class="text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-1">Password updated</h2>
                    <p class="text-sm text-gray-400 font-light mb-6">You have been signed in automatically.</p>
                    <a href="{{ route('portal.loans') }}"
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#E98C00] text-white text-sm font-medium rounded-xl hover:bg-[#C97A00] transition-colors">
                        Go to my loans
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @else
                <form wire:submit.prevent="submit" class="space-y-5">

                    {{-- Email (pre-filled, read-only) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                        <input type="email" wire:model="email" readonly
                               class="w-full px-4 py-2.5 text-sm text-gray-500 bg-[#FEF9E1] border border-gray-200 rounded-xl cursor-not-allowed">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- New password --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">New Password</label>
                        <input type="password" wire:model="password" placeholder="Min 8 characters"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E98C00]/20 focus:border-[#E98C00] transition-colors @error('password') border-red-300 @enderror">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm password --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" placeholder="Repeat password"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E98C00]/20 focus:border-[#E98C00] transition-colors @error('password_confirmation') border-red-300 @enderror">
                        @error('password_confirmation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full py-3 bg-[#E98C00] text-white text-sm font-semibold rounded-xl hover:bg-[#C97A00] transition-colors disabled:opacity-60 flex items-center justify-center gap-2">
                        <span wire:loading.remove>Set New Password</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Updating…
                        </span>
                    </button>

                </form>

                <div class="text-center mt-5">
                    <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">
                        Back to sign in
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
