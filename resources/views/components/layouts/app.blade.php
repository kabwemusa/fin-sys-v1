<!DOCTYPE html>
<html lang="en" data-theme="loansystem">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Credence Systems' }}</title>
    <link rel="dns-prefetch" href="//images.unsplash.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Hero on-load animations ──────────────────────── */
        @keyframes heroUp  { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }
        @keyframes heroIn  { from { opacity:0; }                              to { opacity:1; } }
        .hero-up { animation: heroUp 0.8s cubic-bezier(.16,1,.3,1) both; }
        .hero-in { animation: heroIn 1s ease both; }
        .d1 { animation-delay:.08s; }
        .d2 { animation-delay:.18s; }
        .d3 { animation-delay:.32s; }
        .d4 { animation-delay:.46s; }
        .d5 { animation-delay:.60s; }

        /* ── Scroll-reveal (base + directional) ────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .7s cubic-bezier(.16,1,.3,1),
                        transform .7s cubic-bezier(.16,1,.3,1);
        }
        .reveal-left  { opacity:0; transform:translateX(-32px); transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
        .reveal-right { opacity:0; transform:translateX(32px);  transition: opacity .7s cubic-bezier(.16,1,.3,1), transform .7s cubic-bezier(.16,1,.3,1); }
        .reveal.revealed, .reveal-left.revealed, .reveal-right.revealed { opacity:1; transform:translate(0,0); }

        .reveal.rd1.revealed, .reveal-left.rd1.revealed, .reveal-right.rd1.revealed { transition-delay:.06s; }
        .reveal.rd2.revealed, .reveal-left.rd2.revealed, .reveal-right.rd2.revealed { transition-delay:.15s; }
        .reveal.rd3.revealed, .reveal-left.rd3.revealed, .reveal-right.rd3.revealed { transition-delay:.25s; }
        .reveal.rd4.revealed, .reveal-left.rd4.revealed, .reveal-right.rd4.revealed { transition-delay:.36s; }

        /* ── Marquee ticker ────────────────────────────────── */
        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .marquee-track { animation: marquee 28s linear infinite; width: max-content; }
        .marquee-track:hover { animation-play-state: paused; }

        /* ── Floating hero image layers ─────────────────────── */
        @keyframes floatA { 0%,100% { transform: translateY(0px); }  50% { transform: translateY(-14px); } }
        @keyframes floatB { 0%,100% { transform: translateY(0px); }  50% { transform: translateY(-9px); } }
        @keyframes floatC { 0%,100% { transform: translateY(0px); }  50% { transform: translateY(-6px); } }
        @keyframes floatR { 0%,100% { transform: translateY(0px); }  50% { transform: translateY(10px); } }
        .float-a { animation: floatA 6s ease-in-out infinite; }
        .float-b { animation: floatB 5s ease-in-out 1s infinite; }
        .float-c { animation: floatC 4s ease-in-out 2s infinite; }
        .float-r { animation: floatR 5.5s ease-in-out .5s infinite; }

        /* ── Scroll-dot indicator ────────────────────────────── */
        @keyframes scrollDot { 0%,100% { opacity:1; transform:translateY(0); } 100% { opacity:0; transform:translateY(10px); } }
        .scroll-dot { animation: scrollDot 1.6s ease-in-out infinite; }

        /* ── Stacked card effect ─────────────────────────────── */
        .stacked-card {
            box-shadow:
                0 1px 3px rgba(0,0,0,.04),
                0 6px 0 -3px #e5e7eb,
                0 8px 4px -3px rgba(0,0,0,.06),
                0 12px 0 -6px #d1d5db,
                0 14px 4px -6px rgba(0,0,0,.04);
        }

        /* ── Timeline line draw ─────────────────────────────── */
        .timeline-line { background: linear-gradient(to bottom, transparent, rgba(78,168,217,.25) 20%, rgba(78,168,217,.25) 80%, transparent); }

        /* ── Misc ── */
        .card { border: none !important; }
        input[type=range] { -webkit-appearance: none; appearance: none; height:4px; border-radius:99px; outline:none; cursor:pointer; }
        input[type=range]::-webkit-slider-thumb { -webkit-appearance:none; appearance:none; width:18px; height:18px; border-radius:50%; background:currentColor; cursor:pointer; transition:transform .1s; }
        input[type=range]::-webkit-slider-thumb:active { transform:scale(1.2); }

        /* ── Hero underline squiggle ─────────────────────────── */
        .hero-squiggle { position:relative; display:inline-block; }
        .hero-squiggle::after {
            content:'';
            position:absolute;
            bottom:-6px; left:0; right:0; height:3px;
            background: linear-gradient(90deg, transparent, #F39C12 30%, #F39C12 70%, transparent);
            border-radius: 9px;
            opacity:.55;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }

            .reveal,
            .reveal-left,
            .reveal-right {
                opacity: 1;
                transform: none;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-white font-sans antialiased">
    <x-mary-toast />

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-[#071520]/90 backdrop-blur-md border-b border-white/5">
        <div class="max-w-6xl mx-auto px-5 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="font-semibold text-white text-xl tracking-tight">
                <span class="text-[#4EA8D9]">Credence</span>Systems
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-1.5 rounded-lg hover:bg-white/5">Sign in</a>
                <a href="#products" class="text-sm bg-[#F39C12] text-white px-4 py-2 rounded-xl hover:bg-[#e08c10] transition-colors shadow-md shadow-[#F39C12]/20">Apply Now</a>
            </div>
        </div>
    </nav>

    <div class="pt-16">
        {{ $slot }}
    </div>

    <script>
        // Scroll-reveal via IntersectionObserver — handles reveal, reveal-left, reveal-right
        (function () {
            var selectors = '.reveal:not(.revealed), .reveal-left:not(.revealed), .reveal-right:not(.revealed)';
            var revealAll = function () {
                document.querySelectorAll(selectors).forEach(function (el) {
                    el.classList.add('revealed');
                });
            };

            if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', revealAll);
                } else {
                    revealAll();
                }

                document.addEventListener('livewire:navigated', revealAll);
                return;
            }

            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('revealed');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            function attach() {
                document.querySelectorAll(selectors).forEach(function (el) { io.observe(el); });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', attach);
            } else {
                attach();
            }
            document.addEventListener('livewire:navigated', attach);
        })();
    </script>
</body>
</html>
