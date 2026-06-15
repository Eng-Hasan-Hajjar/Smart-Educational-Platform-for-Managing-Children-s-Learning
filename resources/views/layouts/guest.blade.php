@php
    $__theme  = request()->cookie('theme', 'light');
    $__locale = app()->getLocale();
    $__dir    = $__locale === 'ar' ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ $__locale }}" dir="{{ $__dir }}" data-theme="{{ $__theme }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('app.login')) | {{ __('app.platform_name') }}</title>

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
<body class="{{ $__locale === 'ar' ? 'font-arabic' : 'font-latin' }} bg-app text-main antialiased min-h-screen">

    {{-- عناصر تحكم عائمة: اللغة والمظهر --}}
    <div class="fixed top-4 {{ $__dir === 'rtl' ? 'left-4' : 'right-4' }} z-50 flex items-center gap-2">
        @include('components.language-switcher')
        @include('components.theme-toggle')
    </div>

    @yield('content')

    @stack('scripts')
</body>
</html>