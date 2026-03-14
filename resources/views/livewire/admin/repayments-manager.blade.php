<div class="p-6 lg:p-8 max-w-7xl mx-auto space-y-6">

    <div class="flex border-b border-gray-200 gap-1">
        <button wire:click="$set('activeTab','record')"
            class="px-4 py-2.5 text-sm font-medium transition-all rounded-t-lg {{ $activeTab === 'record' ? 'text-[#1B4F72] border-b-2 border-[#1B4F72]' : 'text-gray-400 hover:text-gray-600' }}">
            Record Repayment
        </button>
        <button wire:click="$set('activeTab','history')"
            class="px-4 py-2.5 text-sm font-medium transition-all rounded-t-lg {{ $activeTab === 'history' ? 'text-[#1B4F72] border-b-2 border-[#1B4F72]' : 'text-gray-400 hover:text-gray-600' }}">
            History
        </button>
        <button wire:click="$set('activeTab','overdue')"
            class="px-4 py-2.5 text-sm font-medium transition-all rounded-t-lg {{ $activeTab === 'overdue' ? 'text-[#1B4F72] border-b-2 border-[#1B4F72]' : 'text-gray-400 hover:text-gray-600' }}">
            Overdue
            @if($overdueLoans->count())
                <span class="ml-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $overdueLoans->count() }}</span>
            @endif
        </button>
    </div>

    @if($activeTab === 'record')
        <div class="max-w-lg">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Record a Repayment</h3>

                {{-- Loan lookup --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Loan Reference</label>
                    <div class="flex gap-2">
                        <input wire:model="loanReference" type="text" placeholder="LN-2026-00001"
                            class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] uppercase"
                            wire:keydown.enter="lookupLoan">
                        <button wire:click="lookupLoan"
                            class="px-4 py-2.5 bg-[#1B4F72] text-white text-sm font-medium rounded-xl hover:bg-[#154060] transition-colors">
                            Lookup
                        </button>
                    </div>
                    @error('loanReference') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                @if($foundLoan)
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-sm">
                        <p class="font-medium text-emerald-700">{{ $foundLoan->reference }}</p>
                        <p class="text-emerald-600 text-xs">{{ $foundLoan->customer->user->name }} &middot; Outstanding: ZMW {{ number_format($foundLoan->outstandingBalance(), 2) }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Amount (ZMW) *</label>
                            <input wire:model="amount" type="number" step="0.01"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                            @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Payment Date *</label>
                            <input wire:model="payment_date" type="date"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Payment Method *</label>
                        <select wire:model="payment_method"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Reference/Receipt No.</label>
                        <input wire:model="reference_number" type="text" placeholder="Optional"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72]">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Notes</label>
                        <textarea wire:model="notes" rows="2"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1B4F72] resize-none"></textarea>
                    </div>

                    <button wire:click="recordRepayment"
                        class="w-full py-3 bg-emerald-600 text-white text-sm font-medium rounded-xl hover:bg-emerald-700 transition-colors">
                        Record Repayment
                    </button>
                @endif
            </div>
        </div>

    @elseif($activeTab === 'history')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="hidden md:grid grid-cols-12 gap-3 px-6 py-3 bg-gray-50 border-b border-gray-100 text-xs font-medium text-gray-400 uppercase tracking-wider">
                <div class="col-span-3">Loan Ref</div>
                <div class="col-span-3">Customer</div>
                <div class="col-span-2 text-right">Amount</div>
                <div class="col-span-2 text-center">Date</div>
                <div class="col-span-2 text-center">Method</div>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentRepayments as $repayment)
                    <div class="grid grid-cols-12 gap-3 px-6 py-3.5 items-center text-sm">
                        <div class="col-span-12 md:col-span-3">
                            <p class="font-medium text-[#1B4F72]">{{ $repayment->loanApplication->reference }}</p>
                        </div>
                        <div class="hidden md:block col-span-3 text-gray-600">{{ $repayment->loanApplication->customer->user->name ?? '—' }}</div>
                        <div class="col-span-6 md:col-span-2 text-right font-semibold text-emerald-600">ZMW {{ number_format($repayment->amount, 2) }}</div>
                        <div class="hidden md:block col-span-2 text-center text-gray-500">{{ $repayment->payment_date->format('d M Y') }}</div>
                        <div class="hidden md:block col-span-2 text-center">
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ str_replace('_',' ',$repayment->payment_method) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-8">No repayments recorded</p>
                @endforelse
            </div>
            @if($recentRepayments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $recentRepayments->links() }}</div>
            @endif
        </div>

    @elseif($activeTab === 'overdue')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($overdueLoans->isEmpty())
                <p class="text-sm text-gray-400 text-center py-12">No overdue loans</p>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($overdueLoans as $loan)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <a href="{{ route('admin.application.review', $loan->id) }}" class="text-sm font-medium text-[#1B4F72] hover:underline">{{ $loan->reference }}</a>
                                <p class="text-xs text-gray-400">{{ $loan->customer->user->name ?? '—' }} &middot; Due {{ $loan->due_date->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-red-500">ZMW {{ number_format($loan->outstandingBalance(), 2) }}</p>
                                <p class="text-xs text-gray-400">{{ $loan->due_date->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
