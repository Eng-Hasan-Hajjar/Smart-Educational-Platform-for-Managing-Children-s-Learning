@extends('layouts.app')
@section('title', $otherParticipant?->name ?? __('app.conversation'))
@section('page-title', $otherParticipant?->name ?? __('app.conversation'))

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    <a href="{{ route('messages.index') }}" class="btn-outline !py-2 !px-3 text-xs inline-flex animate-fade-up">
        <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('app.back') }}
    </a>

    {{-- ══════════ Messages Thread ══════════ --}}
    <div class="card !p-4 sm:!p-5 space-y-3 max-h-[60vh] overflow-y-auto animate-fade-up" id="messagesThread">
        @foreach($conversation->messages as $msg)
        @php $isMine = $msg->sender_id === auth()->id(); @endphp
        <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[75%] {{ $isMine ? 'order-2' : 'order-1' }}">
                <div class="rounded-2xl px-4 py-2.5 {{ $isMine
                        ? 'bg-brand-500 text-white rounded-ee-sm'
                        : 'bg-surface2 text-main rounded-ss-sm' }}">
                    <p class="text-sm leading-relaxed">{{ $msg->body }}</p>
                </div>
                <p class="text-[10px] text-faint mt-1 {{ $isMine ? 'text-end' : 'text-start' }}">
                    {{ $msg->created_at->format('H:i · d/m') }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ══════════ Reply Box ══════════ --}}
    <form method="POST" action="{{ route('messages.reply', $conversation) }}"
          class="card !p-3 flex items-end gap-2 animate-fade-up"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf
        <textarea name="body" rows="1" required
                  class="input resize-none flex-1 !py-2.5"
                  placeholder="{{ __('app.type_message_placeholder') }}"
                  x-data x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"></textarea>
        <button type="submit" :disabled="loading" class="btn-primary !px-4 !py-2.5 flex-shrink-0">
            <svg class="w-5 h-5 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const thread = document.getElementById('messagesThread');
    if (thread) thread.scrollTop = thread.scrollHeight;
</script>
@endpush