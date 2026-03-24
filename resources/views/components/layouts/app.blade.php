<!DOCTYPE html>
<html lang="en" data-theme="CredenceSystems">
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
    {{-- Dark mode: set class before first paint to avoid flash --}}
    <script>
        (function () {
            var t = localStorage.getItem('theme') || 'light';
            if (t === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
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
        .timeline-line { background: linear-gradient(to bottom, transparent, rgba(233,140,0,.25) 20%, rgba(233,140,0,.25) 80%, transparent); }

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
            background: linear-gradient(90deg, transparent, #E98C00 30%, #E98C00 70%, transparent);
            border-radius: 9px;
            opacity:.55;
        }

        /* ── Organic Blob Rectangle shapes ─────────────────────────────
           Rectangular silhouette with irregular organic edge curves.
           Apply to the wrapper, ::before solid inherits the shape.      */
        .blob-rect-1 { border-radius: 18% 14% 16% 20% / 14% 18% 20% 16%; }
        .blob-rect-2 { border-radius: 22% 16% 18% 14% / 16% 22% 14% 18%; }
        .blob-rect-3 { border-radius: 14% 20% 22% 16% / 20% 14% 16% 22%; }
        .blob-rect-4 { border-radius: 20% 12% 14% 18% / 12% 20% 18% 14%; }

        /* ── Photo Float Wrapper ─────────────────────────────────────────
           Usage:
             <div class="photo-float-wrap blob-rect-2 pf-green">
               <img class="photo-float-img" …>
             </div>
           The ::before creates an offset solid beneath the image so the
           photo appears to levitate above a coloured organic solid.        */
        .photo-float-wrap {
            position: relative;
            display: block;
            isolation: isolate;
        }
        .photo-float-wrap::before {
            content: '';
            position: absolute;
            inset: 10px -10px -10px 10px;
            border-radius: inherit;
            z-index: 0;
            opacity: .80;
        }
        .photo-float-wrap.pf-green::before  { background: #E98C00; }
        .photo-float-wrap.pf-navy::before   { background: #C97A00; }
        .photo-float-wrap.pf-gold::before   { background: #E98C00; }
        .photo-float-wrap.pf-teal::before   { background: #E98C00; }
        /* Inner image wrapper — clips photo and tint overlay to the blob shape */
        .photo-float-img {
            position: absolute;
            inset: 0;
            overflow: hidden;
            border-radius: inherit;
            z-index: 1;
        }

        /* ── Sticky CTA ──────────────────────────────────────────────── */
        .sticky-cta-el {
            opacity: 0;
            transform: translateY(14px);
            pointer-events: none;
            transition: opacity .35s cubic-bezier(.16,1,.3,1),
                        transform .35s cubic-bezier(.16,1,.3,1);
        }
        .sticky-cta-el.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
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
<body class="min-h-screen bg-white dark:bg-[#1a0800] font-sans antialiased"
      x-data="{ dark: document.documentElement.classList.contains('dark') }"
      x-init="$watch('dark', function(v) {
          document.documentElement.classList.toggle('dark', v);
          localStorage.setItem('theme', v ? 'dark' : 'light');
      })">
    <x-mary-toast />

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/95 dark:bg-[#1a0800]/90 backdrop-blur-md border-b border-gray-200 dark:border-white/5">
        <div class="max-w-6xl mx-auto h-16 px-4 sm:px-5 flex items-center justify-between gap-3 sm:gap-4 min-w-0">
            <a href="{{ route('home') }}" class="shrink-0 leading-none">
                <img src="{{ asset('images/logo.png') }}" alt="Orange Fin" class="h-9 w-auto">
            </a>
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                {{-- Theme toggle --}}
                <button @click="dark = !dark"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/8 transition-colors"
                        :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                    {{-- Sun (shown in dark mode — click to go light) --}}
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    {{-- Moon (shown in light mode — click to go dark) --}}
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </button>
                <a href="{{ route('login') }}" class="inline-flex items-center whitespace-nowrap text-xs sm:text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors px-2.5 sm:px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">Sign in</a>
                <a href="#products" class="inline-flex items-center whitespace-nowrap text-xs sm:text-sm bg-[#E98C00] text-white px-3 sm:px-4 py-2 rounded-xl hover:bg-[#C97A00] transition-colors shadow-md shadow-[#E98C00]/25">Apply Now</a>
            </div>
        </div>
    </nav>

    <div class="pt-16">
        {{ $slot }}
    </div>

    @unless(View::hasSection('hide-sticky-cta'))
    {{-- ── Sticky Apply CTA ─────────────────────────────────────────────
         Desktop: floating green pill, bottom-right.
         Mobile:  full-width bottom bar with safe-area padding.
         Both are controlled by a single .visible toggle via scroll JS.  --}}

    {{-- Desktop pill --}}
    <div id="sticky-cta-desktop"
         class="sticky-cta-el fixed bottom-6 right-6 z-40 hidden md:flex">
        <a href="#products"
           class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-[#E98C00] text-white text-sm font-semibold
                  shadow-2xl shadow-[#E98C00]/35 hover:bg-[#E98C00] hover:-translate-y-0.5
                  transition-all duration-200 select-none whitespace-nowrap">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Apply Now
        </a>
    </div>

    {{-- Mobile bottom bar --}}
    <div id="sticky-cta-mobile"
         class="sticky-cta-el fixed bottom-0 left-0 right-0 z-40 md:hidden">
        <div class="bg-white/96 backdrop-blur-sm border-t border-gray-100 shadow-2xl shadow-black/10 px-4 pt-3"
             style="padding-bottom: max(0.875rem, env(safe-area-inset-bottom));">
            <a href="#products"
               class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-[#E98C00] text-white text-sm font-semibold
                      shadow-lg shadow-[#E98C00]/30 hover:bg-[#E98C00] active:scale-[.98]
                      transition-all duration-150 select-none">
                Apply for a Loan
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    @endunless

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

    <script>
        // ── Sticky CTA scroll driver ──────────────────────────────────
        // Shows the CTA once the user has scrolled past 90 % of the
        // first viewport height (i.e. past the hero on landing pages).
        (function () {
            var desktop = document.getElementById('sticky-cta-desktop');
            var mobile  = document.getElementById('sticky-cta-mobile');
            if (!desktop && !mobile) return;

            var threshold = window.innerHeight * 0.9;
            var visible   = false;

            function update() {
                var should = window.scrollY > threshold;
                if (should === visible) return;
                visible = should;
                [desktop, mobile].forEach(function (el) {
                    if (!el) return;
                    el.classList.toggle('visible', should);
                });
            }

            window.addEventListener('scroll', update, { passive: true });
            // Re-evaluate after Livewire navigations (SPA-style)
            document.addEventListener('livewire:navigated', function () {
                threshold = window.innerHeight * 0.9;
                visible = false;
                update();
            });
            update();
        })();
    </script>
</body>
</html>
