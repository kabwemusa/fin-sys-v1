<!DOCTYPE html>
<html lang="en" data-theme="loansystem">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — LoanSystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <x-mary-toast />

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             x-on:click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/30 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-30 w-60 bg-[#0c2336] flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

            {{-- Logo --}}
            <div class="flex items-center h-16 px-5 border-b border-white/8 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="text-white font-semibold text-lg tracking-tight">LoanSystem</a>
                <span class="ml-2 text-[10px] text-[#F39C12] bg-[#F39C12]/10 px-2 py-0.5 rounded-full font-medium">Admin</span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                $navItems = [
                    ['route' => 'admin.dashboard',    'label' => 'Dashboard',      'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.applications', 'label' => 'Applications',   'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'admin.customers',    'label' => 'Customers',      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.products',     'label' => 'Loan Products',  'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['route' => 'admin.repayments',   'label' => 'Repayments',     'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'admin.reports',      'label' => 'Reports',        'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'admin.audit-log',    'label' => 'Audit Log',      'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ];
                @endphp

                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                              {{ $isActive ? 'bg-white/10 text-white font-medium' : 'text-slate-400 hover:bg-white/6 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-[#F39C12]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Logout --}}
            <div class="px-3 py-4 border-t border-white/8 shrink-0">
                @auth
                <div class="flex items-center gap-3 px-3 py-2 mb-2">
                    <div class="w-7 h-7 rounded-full bg-[#F39C12]/20 flex items-center justify-center shrink-0">
                        <span class="text-[#F39C12] text-xs font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-slate-500 text-[11px] truncate">Administrator</p>
                    </div>
                </div>
                @endauth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2 rounded-xl text-sm text-slate-400 hover:bg-white/6 hover:text-white transition-all duration-150">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-100 shrink-0">
                <button x-on:click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-sm font-medium text-gray-700 hidden lg:block">{{ $title ?? 'Admin Panel' }}</h1>
                <div class="ml-auto flex items-center gap-3">
                    <span class="text-xs text-gray-400 hidden sm:block">{{ now()->format('d M Y') }}</span>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
