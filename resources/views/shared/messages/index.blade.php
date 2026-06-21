@extends('layouts.app')
@section('title', __('app.messages'))
@section('page-title', __('app.messages'))
@section('page-subtitle', __('app.messages_subtitle'))

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between animate-fade-up">
        <p class="text-muted text-sm">{{ $conversations->total() }} {{ __('app.conversation') }}</p>
        <a href="{{ route('messages.compose') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('app.new_message') }}
        </a>
    </div>

    @forelse($conversations as $conv)
    @php $other = $conv->participants->first(); @endphp
    <a href="{{ route('messages.show', $conv) }}"
       class="card card-hover flex items-center gap-3 animate-fade-up {{ $conv->unread_count > 0 ? 'border-brand-400/30 bg-brand-50/30' : '' }}"
       style="animation-delay:{{ .02 * $loop->index }}s">

        <img src="{{ $other?->avatar_url }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0" alt="">

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="font-bold text-main truncate">{{ $other?->name ?? __('app.unknown_user') }}</p>
                <span class="text-xs text-faint flex-shrink-0">{{ $conv->latestMessage?->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-muted truncate mt-0.5">
                {{ $conv->latestMessage?->body ?? '—' }}
            </p>
        </div>

        @if($conv->unread_count > 0)
        <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs font-black flex items-center justify-center flex-shrink-0">
            {{ $conv->unread_count }}
        </span>
        @endif
    </a>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">💬</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('app.no_conversations') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('app.no_conversations_hint') }}</p>
        <a href="{{ route('messages.compose') }}" class="btn-primary mt-5 inline-flex">
            {{ __('app.start_conversation') }}
        </a>
    </div>
    @endforelse

    @if($conversations->hasPages())
    <div class="flex justify-center">{{ $conversations->links() }}</div>
    @endif
</div>
@endsection