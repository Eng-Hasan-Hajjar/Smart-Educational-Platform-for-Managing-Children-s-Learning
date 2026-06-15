@php
    // تحديد الـ Theme: من حساب المستخدم، أو من الكوكي، أو افتراضي 'light'
    $__theme = auth()->check()
        ? (auth()->user()->theme ?? 'light')
        : request()->cookie('theme', 'light');

    $__locale = app()->getLocale();
    $__dir    = $__locale === 'ar' ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ $__locale }}" dir="{{ $__dir }}" data-theme="{{ $__theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.dashboard')) | {{ __('app.platform_name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/nour-theme.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['selector', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        arabic: ['Tajawal', 'sans-serif'],
                        latin:  ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        app:     'var(--bg-app)',
                        surface: 'var(--bg-surface)',
                        surface2:'var(--bg-surface-2)',
                        hover:   'var(--bg-hover)',
                        bd:      'var(--border-app)',
                        bds:     'var(--border-strong)',
                        main:    'var(--text-main)',
                        muted:   'var(--text-muted)',
                        faint:   'var(--text-faint)',
                        brand: {
                            50:'var(--brand-50)', 100:'var(--brand-100)', 200:'var(--brand-200)',
                            400:'var(--brand-400)', 500:'var(--brand-500)', 600:'var(--brand-600)', 700:'var(--brand-700)'
                        },
                        accent: { 50:'var(--accent-50)', 400:'var(--accent-400)', 500:'var(--accent-500)', 600:'var(--accent-600)' },
                        success:{ 50:'var(--success-50)', 500:'var(--success-500)', 600:'var(--success-600)' },
                        danger: { 50:'var(--danger-50)', 500:'var(--danger-500)', 600:'var(--danger-600)' },
                        warning:{ 50:'var(--warning-50)', 500:'var(--warning-500)', 600:'var(--warning-600)' },
                        info:   { 50:'var(--info-50)', 500:'var(--info-500)', 600:'var(--info-600)' },
                    },
                    boxShadow: {
                        glow: 'var(--shadow-glow)',
                        'glow-strong': 'var(--shadow-glow-strong)',
                    },
                    borderRadius: { '2xl': '1rem', '3xl': '1.5rem' },
                }
            }
        }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="{{ $__locale === 'ar' ? 'font-arabic' : 'font-latin' }} bg-app text-main antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: true, mobileOpen: false }">

    {{-- ══════════ Mobile Overlay ══════════ --}}
    <div x-show="mobileOpen" x-cloak @click="mobileOpen=false"
         class="sidebar-overlay fixed inset-0 z-40 lg:hidden animate-fade"></div>

    {{-- ══════════ SIDEBAR ══════════ --}}
    <aside
        :class="[
            sidebarOpen ? 'w-72' : 'w-0 lg:w-20',
            mobileOpen ? 'translate-x-0' : (
                '{{ $__dir }}' === 'rtl' ? 'translate-x-full lg:translate-x-0' : '-translate-x-full lg:translate-x-0'
            )
        ]"
        class="sidebar-shell fixed lg:sticky top-0 z-50 h-screen flex-shrink-0 flex flex-col
               transition-all duration-300 ease-[cubic-bezier(.22,1,.36,1)] overflow-hidden
               {{ $__dir === 'rtl' ? 'right-0' : 'left-0' }}">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 flex-shrink-0">
            <div class="logo-orb">🎓</div>
            <div x-show="sidebarOpen" x-cloak class="animate-fade overflow-hidden whitespace-nowrap">
                <p class="text-white font-extrabold text-sm leading-tight">{{ __('app.platform_name') }}</p>
                <p class="text-white/45 text-[11px]">{{ $__locale === 'ar' ? 'Nour Platform' : 'منصة نور' }}</p>
            </div>
        </div>

        <div class="divider-glow mx-5"></div>

        {{-- User Card --}}
        <div class="px-4 py-4 flex-shrink-0">
            <div class="flex items-center gap-3 {{ true ? '' : '' }}">
                <div class="avatar-ring flex-shrink-0">
                    <img src="{{ auth()->user()->avatar_url }}" class="w-10 h-10 object-cover" alt="">
                </div>
                <div x-show="sidebarOpen" x-cloak class="min-w-0 animate-fade">
                    <p class="text-white text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-white/45 text-[11px] truncate">{{ __('app.' . (auth()->user()->roles->first()?->name ?? 'student')) }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 pb-4 overflow-y-auto space-y-1.5">
            @include('components.sidebar')
        </nav>

        {{-- Bottom --}}
        <div class="px-4 py-4 flex-shrink-0 space-y-1.5">
            <div class="divider-glow mb-2"></div>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="nav-icon">⚙️</span>
                <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.profile') }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-full">
                    <span class="nav-icon">🚪</span>
                    <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.logout') }}</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════ MAIN ══════════ --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Topbar --}}
        <header class="bg-surface/80 backdrop-blur-md border-b border-bd px-4 sm:px-6 py-3.5
                        flex items-center justify-between sticky top-0 z-30">

            <div class="flex items-center gap-3">
                {{-- Mobile toggle --}}
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden theme-toggle">
                    <svg class="!relative !opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Desktop toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex theme-toggle">
                    <svg class="!relative !opacity-100 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>

                <div>
                    <h1 class="font-extrabold text-main text-base sm:text-lg leading-tight">@yield('page-title', __('app.dashboard'))</h1>
                    @hasSection('page-subtitle')
                    <p class="text-muted text-xs mt-0.5">@yield('page-subtitle')</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">

                {{-- Language Switcher --}}
                @include('components.language-switcher')

                {{-- Theme Toggle --}}
                @include('components.theme-toggle')

                {{-- Notifications --}}
                <div x-data="{ open:false, count:0 }" x-init="fetch('{{ route('notifications.count') }}').then(r=>r.json()).then(d=>count=d.count)" class="relative">
                    <button @click="open=!open" class="theme-toggle relative">
                        <svg class="!relative !opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="count>0" x-text="count" x-cloak class="notif-dot"></span>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open=false"
                         x-transition:enter="animate-scale-in"
                         class="dropdown-panel absolute {{ $__dir === 'rtl' ? 'left-0' : 'right-0' }} mt-2 w-80 z-50 overflow-hidden origin-top-{{ $__dir === 'rtl' ? 'left' : 'right' }}">
                        @include('components.notifications-dropdown')
                    </div>
                </div>

                {{-- Messages --}}
                <a href="{{ route('messages.index') }}" class="theme-toggle">
                    <svg class="!relative !opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
            </div>
        </header>

        {{-- Alerts --}}
        <div class="px-4 sm:px-6 pt-4 space-y-2">
            @if(session('success'))
            <div class="card !p-3.5 flex items-center gap-3 border-success-500/30 bg-success-50 text-success-600 animate-fade-up">
                <span class="text-lg">✅</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="card !p-3.5 flex items-center gap-3 border-danger-500/30 bg-danger-50 text-danger-600 animate-fade-up">
                <span class="text-lg">❌</span>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            @endif
            @if(session('info'))
            <div class="card !p-3.5 flex items-center gap-3 border-info-500/30 bg-info-50 text-info-600 animate-fade-up">
                <span class="text-lg">ℹ️</span>
                <span class="text-sm font-medium">{{ session('info') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="card !p-3.5 bg-danger-50 border-danger-500/30 animate-fade-up">
                <ul class="list-disc list-inside text-danger-600 text-sm space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 px-4 sm:px-6 pb-10 pt-4 animate-fade-up">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="text-center py-4 text-xs text-faint border-t border-bd">
            © {{ date('Y') }} {{ __('app.platform_name') }} — {{ $__locale === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>