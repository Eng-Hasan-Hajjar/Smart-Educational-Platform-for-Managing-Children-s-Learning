<div class="flex items-center justify-between px-4 py-3.5 border-b border-bd">
    <span class="font-bold text-sm text-main flex items-center gap-2">
        🔔 {{ __('app.notifications') }}
    </span>
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        <button class="text-xs text-brand-500 hover:text-brand-700 font-semibold transition">
            {{ __('app.mark_all_read') }}
        </button>
    </form>
</div>

<div class="max-h-80 overflow-y-auto divide-y divide-bd">
    @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $n)
    <div class="px-4 py-3 hover:bg-hover transition flex items-start gap-3 {{ $n->read_at ? '' : 'bg-brand-50/60' }}">
        <span class="text-lg flex-shrink-0 mt-0.5">
            @if(!$n->read_at)
                <span class="relative flex w-2 h-2">
                    <span class="absolute inline-flex w-full h-full rounded-full bg-brand-500 opacity-75 animate-pulse-glow"></span>
                    <span class="relative inline-flex rounded-full w-2 h-2 bg-brand-500"></span>
                </span>
            @else
                <span class="inline-flex w-2 h-2 rounded-full bg-bds"></span>
            @endif
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-sm text-main leading-relaxed">{{ $n->data['message'] ?? __('app.new_notification') }}</p>
            <p class="text-xs text-faint mt-1">{{ $n->created_at->diffForHumans() }}</p>
        </div>
    </div>
    @empty
    <div class="px-4 py-10 text-center">
        <p class="text-3xl mb-2">🔔</p>
        <p class="text-muted text-sm">{{ __('app.no_notifications') }}</p>
    </div>
    @endforelse
</div>

<div class="p-3 border-t border-bd">
    <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-bold text-brand-500 hover:text-brand-700 transition py-1">
        {{ __('app.view_all') }} →
    </a>
</div>