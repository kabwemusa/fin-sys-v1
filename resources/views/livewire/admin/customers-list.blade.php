<div class="p-6 lg:p-8 max-w-7xl mx-auto space-y-5">

    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, email, phone, NRC…"
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800
                       focus:outline-none focus:ring-2 focus:ring-[#166534]/20 focus:border-[#166534] transition-all">
        </div>
    </div>

    <div class="portal-panel rounded-2xl overflow-hidden">
        <div class="hidden md:grid grid-cols-12 gap-3 px-6 py-3 bg-gray-50 border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
            <div class="col-span-4">Customer</div>
            <div class="col-span-2">Phone</div>
            <div class="col-span-2">NRC</div>
            <div class="col-span-2 text-center">Applications</div>
            <div class="col-span-1 text-center">Joined</div>
            <div class="col-span-1"></div>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($customers as $customer)
                <a href="{{ route('admin.customer.detail', $customer->id) }}"
                   class="portal-card-hover grid grid-cols-12 gap-3 px-6 py-4 hover:bg-gray-50/60 transition-colors items-center group">
                    <div class="col-span-12 md:col-span-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#166534]/10 flex items-center justify-center shrink-0">
                            <span class="text-[#166534] text-xs font-semibold">{{ strtoupper(substr($customer->user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800 group-hover:text-[#166534] transition-colors">{{ $customer->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $customer->user->email }}</p>
                        </div>
                    </div>
                    <div class="hidden md:block col-span-2">
                        <p class="text-sm text-gray-600">{{ $customer->user->phone ?? '—' }}</p>
                    </div>
                    <div class="hidden md:block col-span-2">
                        <p class="text-sm text-gray-600">{{ $customer->nrc_number }}</p>
                    </div>
                    <div class="col-span-6 md:col-span-2 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-medium text-gray-600">
                            {{ $customer->loan_applications_count }}
                        </span>
                    </div>
                    <div class="hidden md:block col-span-1 text-center">
                        <p class="text-xs text-gray-400">{{ $customer->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-span-6 md:col-span-1 flex justify-end">
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-[#166534] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-400">No customers found</p>
                </div>
            @endforelse
        </div>

        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
