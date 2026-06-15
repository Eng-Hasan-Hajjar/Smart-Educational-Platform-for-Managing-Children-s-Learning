<button onclick="window.__nourToggleTheme()" class="theme-toggle" aria-label="{{ __('app.switch_theme') }}" title="{{ __('app.switch_theme') }}">
    {{-- Sun icon (يظهر في الوضع الفاتح) --}}
    <svg class="icon-sun" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.36 6.36l-.7-.7M6.34 6.34l-.7-.7m12.72 0l-.7.7M6.34 17.66l-.7.7M12 7a5 5 0 100 10 5 5 0 000-10z"/>
    </svg>
    {{-- Moon icon (يظهر في الوضع الداكن) --}}
    <svg class="icon-moon" fill="currentColor" viewBox="0 0 24 24">
        <path d="M21.64 13a1 1 0 00-1.05-.14 8.05 8.05 0 01-3.37.73 8.15 8.15 0 01-8.14-8.1 8.59 8.59 0 01.25-2A1 1 0 008 2.36a10.14 10.14 0 1014 11.69 1 1 0 00-.36-1.05z"/>
    </svg>
</button>

<script>
    if (!window.__nourToggleTheme) {
        window.__nourToggleTheme = function () {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);

            fetch('{{ url('/theme') }}/' + next, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            });
        };
    }
</script>