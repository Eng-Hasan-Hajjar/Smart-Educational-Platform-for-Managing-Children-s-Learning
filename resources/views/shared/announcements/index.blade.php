@extends('layouts.app')
@section('title', __('app.announcements'))
@section('page-title', __('app.announcements'))
@section('page-subtitle', __('app.announcements_subtitle'))

@section('content')
<div class="space-y-5">

    @hasanyrole('super_admin|school_admin')
    <div class="card animate-fade-up" x-data="{ open: false }">
        <button @click="open = !open" type="button" class="w-full flex items-center justify-between">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">📢</span>
                {{ __('app.new_announcement') }}
            </h3>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-faint transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak x-transition class="mt-5 pt-5 border-t border-bd">
            <form method="POST" action="{{ route('announcements.store') }}" class="space-y-4"
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <div>
                    <label class="label">{{ __('app.title') }} *</label>
                    <input type="text" name="title" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.body') }} *</label>
                    <textarea name="body" rows="4" required class="input resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="label">{{ __('app.type') }}</label>
                        <select name="type" class="input">
                            <option value="general">{{ __('app.type_general') }}</option>
                            <option value="academic">{{ __('app.type_academic') }}</option>
                            <option value="urgent">{{ __('app.type_urgent') }}</option>
                            <option value="event">{{ __('app.type_event') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">{{ __('app.target_audience') }}</label>
                        <select name="target_type" class="input">
                            <option value="all">{{ __('app.all') }}</option>
                            <option value="teachers">{{ __('app.teachers') }}</option>
                            <option value="students">{{ __('app.students_label') }}</option>
                            <option value="parents">{{ __('app.parents_label') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">{{ __('app.expires_at') }}</label>
                        <input type="date" name="expires_at" class="input">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" :disabled="loading" class="btn-primary">📢 {{ __('app.publish') }}</button>
                </div>
            </form>
        </div>
    </div>
    @endhasanyrole

    @forelse($announcements as $a)
    @php
        $isRead = in_array($a->id, $readIds);
        $priorityStyle = match($a->type) {
            'urgent'   => ['border-danger-500/30 bg-danger-50', 'danger', '🚨'],
            'academic' => ['border-warning-500/30 bg-warning-50', 'warning', '📚'],
            'event'    => ['border-info-500/30 bg-info-50', 'info', '📅'],
            default    => ['border-bd', 'brand', '📢'],
        };
    @endphp
    <div class="card !p-4 animate-fade-up {{ $priorityStyle[0] }} {{ !$isRead ? 'shadow-glow' : '' }}"
         style="animation-delay:{{ .03 * $loop->index }}s"
         @if(!$isRead)
         x-data x-init="fetch('{{ route('announcements.read', $a) }}', {method:'POST',headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}})"
         @endif>
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-{{ $priorityStyle[1] }}-50 text-{{ $priorityStyle[1] }}-600 flex items-center justify-center text-xl flex-shrink-0">
                {{ $priorityStyle[2] }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <p class="font-bold text-main">{{ $a->title }}</p>
                    @if(!$isRead)
                    <span class="badge-brand text-[10px]">{{ __('app.new') }}</span>
                    @endif
                </div>
                <p class="text-sm text-muted leading-relaxed">{{ $a->body }}</p>
                <p class="text-xs text-faint mt-2">{{ $a->createdBy->name }} · {{ $a->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📢</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('app.no_announcements') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('app.no_announcements_hint') }}</p>
    </div>
    @endforelse

    @if($announcements->hasPages())
    <div class="flex justify-center">{{ $announcements->links() }}</div>
    @endif
</div>
@endsection
