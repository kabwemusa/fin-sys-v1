<!DOCTYPE html>
<html lang="en" data-theme="loansystem">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Customer Portal' }} — LoanSystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">
    <x-mary-toast />

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- ── Mobile overlay ── --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-on:click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/30 lg:hidden"></div>

        {{-- ── Sidebar ── --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-[#0c2336] flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto"
        >
            {{-- Logo --}}
            <div class="flex items-center h-16 px-6 border-b border-white/8 flex-shrink-0">
                <a href="{{ route('portal.loans') }}" class="text-white font-semibold text-lg tracking-tight">LoanSystem</a>
                <span class="ml-2 text-[10px] text-[#4EA8D9] bg-[#4EA8D9]/10 px-2 py-0.5 rounded-full font-medium">Portal</span>
            </div>

            {{-- Customer info --}}
            @auth
            <div class="px-5 py-4 border-b border-white/8 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#1B4F72] flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-slate-400 text-xs truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
            @endauth

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'portal.loans',   'label' => 'My Loans',  'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'portal.profile', 'label' => 'Profile',   'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                              {{ $isActive ? 'bg-white/10 text-white font-medium' : 'text-slate-400 hover:bg-white/6 hover:text-white' }}">
                        <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-[#4EA8D9]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Bottom: Logout --}}
            <div class="px-3 py-4 border-t border-white/8 flex-shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm text-slate-400 hover:bg-white/6 hover:text-white transition-all duration-150">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Main content ── --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            {{-- Top header bar --}}
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-100 flex-shrink-0">
                {{-- Hamburger (mobile) --}}
                <button x-on:click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Page title --}}
                <h1 class="text-sm font-medium text-gray-700 hidden lg:block">{{ $title ?? 'Customer Portal' }}</h1>

                {{-- Right: notifications + name --}}
                <div class="flex items-center gap-4 ml-auto">
                    @auth
                    <span class="text-sm text-gray-500 hidden sm:block">{{ auth()->user()->name }}</span>
                    @endauth
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
