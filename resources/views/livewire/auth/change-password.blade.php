<div class="min-h-screen bg-[#FEF9E1] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#E98C00]/10 mb-4">
                <svg class="w-7 h-7 text-[#E98C00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-1">Set Your Password</h1>
            <p class="text-sm text-gray-400 font-light">Choose a strong password to secure your account</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 p-8">
            <form wire:submit.prevent="save" class="space-y-4">

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input wire:model="new_password" type="password" placeholder="Min. 8 characters" autocomplete="new-password"
                            class="w-full pl-10 pr-4 py-3 bg-[#FEF9E1] border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-[#E98C00]/20 focus:border-[#E98C00] transition-all duration-200">
                    </div>
                    @error('new_password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <input wire:model="new_password_confirmation" type="password" placeholder="Repeat password" autocomplete="new-password"
                            class="w-full pl-10 pr-4 py-3 bg-[#FEF9E1] border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-[#E98C00]/20 focus:border-[#E98C00] transition-all duration-200">
                    </div>
                </div>

                <div class="h-px bg-linear-to-r from-transparent via-gray-200 to-transparent"></div>

                <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                    class="w-full flex items-center justify-center gap-2 py-3 bg-[#E98C00] text-white rounded-xl text-sm font-medium
                           hover:bg-[#C97A00] transition-all duration-200 shadow-md shadow-blue-900/20">
                    <span wire:loading.remove>Save Password &amp; Continue</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Saving…
                    </span>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6 font-light">
            You must set a password before accessing your portal.
        </p>
    </div>
</div>
