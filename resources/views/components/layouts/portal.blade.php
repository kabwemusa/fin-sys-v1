<!DOCTYPE html>
<html lang="en" data-theme="CredenceSystems">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Customer Portal' }} - Credence Systems</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="portal-shell-bg min-h-screen font-sans antialiased text-slate-800">
    <x-mary-toast />

    <div x-data="portalShell()" class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-on:click="closeSidebar()"
             class="portal-backdrop fixed inset-0 z-20 bg-[#071520]/55 lg:hidden"></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="portal-sidebar-surface fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-white/8 transform transition-transform duration-300 ease-in-out lg:static lg:z-auto lg:translate-x-0"
        >
            <div class="flex h-16 items-center border-b border-white/8 px-6 shrink-0">
                <a href="{{ route('portal.loans') }}" class="portal-action text-lg font-semibold tracking-tight text-white">CredenceSystems</a>
                <span class="ml-2 rounded-full border border-[#4EA8D9]/20 bg-[#4EA8D9]/10 px-2 py-0.5 text-[10px] font-medium text-[#4EA8D9]">Portal</span>
            </div>

            @auth
            <div class="border-b border-white/8 px-5 py-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#4EA8D9]/15 ring-1 ring-white/8 shrink-0">
                        <span class="text-sm font-medium text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
            @endauth

            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                @php
                    $navItems = [
                        ['route' => 'portal.loans', 'label' => 'My Loans', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'portal.profile', 'label' => 'Profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="portal-nav-link flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-all duration-150 {{ $isActive ? 'bg-white/10 font-medium text-white shadow-lg shadow-black/10' : 'text-slate-400 hover:bg-white/6 hover:text-white' }}">
                        <svg class="h-4 w-4 shrink-0 {{ $isActive ? 'text-[#4EA8D9]' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-white/8 px-3 py-4 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="portal-action flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-400 transition-all duration-150 hover:bg-white/6 hover:text-white">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="relative flex min-w-0 flex-1 flex-col overflow-hidden">
            <div wire:loading.delay.flex class="portal-progress-track pointer-events-none absolute inset-x-0 top-0 z-30 hidden h-1">
                <span class="portal-progress-runner absolute left-0 top-0 h-full w-28 rounded-full bg-linear-to-r from-[#166534] via-[#4EA8D9] to-[#F39C12]"></span>
            </div>

            <header class="portal-topbar-surface flex h-16 items-center justify-between border-b border-white/70 px-6 shadow-sm shadow-slate-900/5 shrink-0">
                <button x-on:click="toggleSidebar()" class="portal-action rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-gray-50 hover:text-gray-600 lg:hidden">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="hidden text-sm font-medium text-gray-700 lg:block">{{ $title ?? 'Customer Portal' }}</h1>

                <div class="ml-auto flex items-center gap-3 sm:gap-4">
                    <div wire:loading.delay.longer.flex class="hidden items-center gap-2 rounded-full border border-[#4EA8D9]/20 bg-[#4EA8D9]/10 px-3 py-1.5 text-xs font-medium text-[#1B4F72] md:flex">
                        <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Updating your portal
                    </div>
                    @auth
                    <span class="hidden text-sm text-gray-500 sm:block">{{ auth()->user()->name }}</span>
                    @endauth
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
