<div class="p-6 lg:p-8 max-w-6xl mx-auto space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Loan Products</h2>
            <p class="text-sm text-gray-400 font-light mt-0.5">Manage active loan products</p>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#E98C00] text-white text-sm font-medium rounded-xl hover:bg-[#C97A00] transition-colors shadow-sm">
            + New Product
        </button>
    </div>

    {{-- Product cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-50 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $product->name }}</p>
                        <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_',' ',$product->type)) }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-[#FEF9E1] text-gray-400' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="px-5 py-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-400">Rate</span><span class="font-semibold text-[#E98C00]">{{ $product->interest_rate }}%/mo</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Amount</span><span class="text-gray-700">ZMW {{ number_format($product->min_amount,0) }} – {{ number_format($product->max_amount,0) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Tenure</span><span class="text-gray-700">{{ $product->min_tenure_months }}–{{ $product->max_tenure_months }} months</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Collateral</span><span class="text-gray-700">{{ $product->requires_collateral ? 'Required' : 'Not required' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Applications</span><span class="font-medium text-gray-700">{{ $product->loan_applications_count }}</span></div>
                </div>
                <div class="px-5 pb-4 flex gap-2">
                    <button wire:click="openEdit({{ $product->id }})"
                        class="flex-1 py-2 text-xs font-medium text-[#E98C00] bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        Edit
                    </button>
                    <button wire:click="toggleActive({{ $product->id }})"
                        class="flex-1 py-2 text-xs font-medium {{ $product->is_active ? 'text-gray-500 bg-[#FEF9E1] hover:bg-gray-200' : 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' }} rounded-lg transition-colors">
                        {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Slide-in Form Panel --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-end">
            <div class="absolute inset-0 bg-black/30" wire:click="$set('showForm', false)"></div>
            <div class="relative bg-white h-full w-full max-w-lg shadow-2xl overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between z-10">
                    <h3 class="text-base font-semibold text-gray-800">{{ $editingId ? 'Edit Product' : 'New Product' }}</h3>
                    <button wire:click="$set('showForm', false)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-[#FEF9E1] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Product Name *</label>
                        <input wire:model="name" type="text" placeholder="e.g. Quick Cash Loan"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Type *</label>
                        <select wire:model="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                            <option value="salary_backed">Salary Backed</option>
                            <option value="collateral_backed">Collateral Backed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00] resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Min Amount (ZMW) *</label>
                            <input wire:model="min_amount" type="number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                            @error('min_amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Max Amount (ZMW) *</label>
                            <input wire:model="max_amount" type="number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                            @error('max_amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Min Tenure (months) *</label>
                            <input wire:model="min_tenure" type="number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Max Tenure (months) *</label>
                            <input wire:model="max_tenure" type="number" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Interest Rate (%/month) *</label>
                        <input wire:model="interest_rate" type="number" step="0.01" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#E98C00]">
                        @error('interest_rate') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="requires_collateral" type="checkbox" class="rounded">
                            <span class="text-sm text-gray-700">Requires Collateral</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="is_active" type="checkbox" class="rounded">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('showForm', false)"
                            class="flex-1 py-2.5 rounded-xl text-sm font-medium text-gray-500 border border-gray-200 hover:bg-[#FEF9E1] transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 py-2.5 rounded-xl text-sm font-medium bg-[#E98C00] text-white hover:bg-[#C97A00] transition-colors">
                            {{ $editingId ? 'Update' : 'Create' }} Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
