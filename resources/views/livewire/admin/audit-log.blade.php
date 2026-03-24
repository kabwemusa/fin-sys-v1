<div class="p-6 lg:p-8 max-w-6xl mx-auto space-y-5">

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search actions…"
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00] transition-all">
        </div>
        <input wire:model.live="dateFrom" type="date"
            class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
        <input wire:model.live="dateTo" type="date"
            class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-50">
            @forelse($activities as $activity)
                <div class="px-6 py-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#E98C00]/10 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-[#E98C00] text-xs font-semibold">{{ strtoupper(substr($activity->causer?->name ?? 'S', 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">{{ $activity->causer?->name ?? 'System' }}</span>
                                    <span class="text-gray-400"> {{ $activity->description }}</span>
                                    @if($activity->subject_type)
                                        <span class="text-gray-400"> on </span>
                                        <span class="font-medium text-[#E98C00]">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                    @endif
                                </p>
                                @if($activity->properties->count())
                                    <div class="mt-1 text-xs text-gray-400 font-mono">
                                        @foreach($activity->properties->get('attributes', []) as $key => $val)
                                            <span class="mr-2">{{ $key }}: {{ is_array($val) ? json_encode($val) : $val }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 shrink-0">{{ $activity->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-12">No activity in this period</p>
            @endforelse
        </div>
        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $activities->links() }}</div>
        @endif
    </div>
</div>
