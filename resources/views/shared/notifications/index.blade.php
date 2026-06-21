@extends('layouts.app')
@section('title', __('app.notifications'))
@section('page-title', __('app.notifications'))
@section('page-subtitle', __('app.notifications_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    @if($notifications->total())
    <div class="flex items-center justify-between animate-fade-up">
        <p class="text-muted text-sm">{{ $notifications->total() }} {{ __('app.notification') }}</p>
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                ✓ {{ __('app.mark_all_read') }}
            </button>
        </form>
    </div>
    @endif

    {{-- ══════════ List ══════════ --}}
    @forelse($notifications as $n)
    <div class="card !p-4 animate-fade-up flex items-start gap-3
                {{ is_null($n->read_at) ? 'border-brand-400/30 bg-brand-50/40' : '' }}"
         style="animation-delay:{{ .02 * $loop->index }}s">

        @if(is_null($n->read_at))
        <span class="relative flex w-2.5 h-2.5 mt-1.5 flex-shrink-0">
            <span class="absolute inline-flex w-full h-full rounded-full bg-brand-500 opacity-75 animate-pulse-glow"></span>
            <span class="relative inline-flex rounded-full w-2.5 h-2.5 bg-brand-500"></span>
        </span>
        @else
        <span class="w-2.5 h-2.5 mt-1.5 flex-shrink-0"></span>
        @endif

        <div class="flex-1 min-w-0">
            <p class="text-sm text-main leading-relaxed">{{ $n->data['message'] ?? __('app.new_notification') }}</p>
            <p class="text-xs text-faint mt-1">{{ $n->created_at->diffForHumans() }}</p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            @if(is_null($n->read_at))
            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                @csrf
                <button class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                    {{ __('app.mark_read') }}
                </button>
            </form>
            @endif
            <form method="POST" action="{{ route('notifications.destroy', $n->id) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button class="text-faint hover:text-danger-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">🔔</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('app.no_notifications') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('app.no_notifications_hint') }}</p>
    </div>
    @endforelse

    @if($notifications->hasPages())
    <div class="flex justify-center">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection