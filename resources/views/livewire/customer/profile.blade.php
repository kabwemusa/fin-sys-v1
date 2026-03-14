<div class="p-6 lg:p-8 max-w-2xl mx-auto">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">My Profile</h2>
        <p class="text-sm text-gray-400 mt-0.5 font-light">Update your personal contact and address details</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <p class="text-sm text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Account Details --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Account Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Full Name *</label>
                    <input wire:model="name" type="text" placeholder="John Banda"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Phone Number *</label>
                    <input wire:model="phone" type="text" placeholder="+260 97XXXXXXX"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Email Address *</label>
                    <input wire:model="email" type="email" placeholder="you@example.com"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Read-only NRC --}}
                @if($nrc_number)
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">NRC Number <span class="text-gray-300">(read-only)</span></label>
                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl">
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        <span class="text-sm text-gray-500 font-medium">{{ $nrc_number }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Address --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#1B4F72]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Address</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Residential Address</label>
                    <textarea wire:model="residential_address" rows="2" placeholder="House No. 12, Kabulonga Road..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200 resize-none"></textarea>
                    @error('residential_address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">City</label>
                    <input wire:model="city" type="text" placeholder="Lusaka"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    @error('city') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Province</label>
                    <input wire:model="province" type="text" placeholder="Lusaka Province"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1B4F72]/20 focus:border-[#1B4F72] transition-all duration-200">
                    @error('province') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Save button --}}
        <div class="flex justify-end">
            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-70 cursor-not-allowed"
                class="inline-flex items-center gap-2 px-7 py-3 bg-[#1B4F72] text-white text-sm font-medium rounded-xl
                       hover:bg-[#154060] transition-all duration-200 shadow-md shadow-blue-900/20">
                <span wire:loading.remove>Save Changes</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Saving…
                </span>
            </button>
        </div>

    </form>
</div>
