@extends('layouts.app')
@section('title', __('app.lessons'))
@section('page-title', __('app.lessons'))
@section('page-subtitle', __('teacher.lessons_subtitle'))

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $lessons->total() }} {{ __('teacher.lesson') }}</p>
        <a href="{{ route('teacher.lessons.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            {{ __('teacher.add_lesson') }}
        </a>
    </div>

    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" class="input ps-10" placeholder="{{ __('teacher.search_lessons') }}">
            </div>
            <select name="status" class="input sm:w-44">
                <option value="">{{ __('app.all') }}</option>
                <option value="published" {{ request('status')==='published'?'selected':'' }}>{{ __('status.published') }}</option>
                <option value="draft" {{ request('status')==='draft'?'selected':'' }}>{{ __('status.draft') }}</option>
            </select>
            <button type="submit" class="btn-outline">🔍 {{ __('app.filter') }}</button>
        </form>
    </div>

    @forelse($lessons as $lesson)
    <div class="card card-hover animate-fade-up" style="animation-delay:{{ .04 + $loop->index * .03 }}s">
        <div class="flex items-center gap-4">
            <img src="{{ $lesson->thumbnail_url }}" class="w-16 h-16 rounded-2xl object-cover flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge-{{ $lesson->status === 'published' ? 'green' : 'gray' }}">{{ __('status.'.$lesson->status) }}</span>
                    @if($lesson->is_free)<span class="badge-info">{{ __('teacher.free') }}</span>@endif
                </div>
                <p class="font-bold text-main truncate">{{ $lesson->title }}</p>
                <p class="text-xs text-muted">{{ $lesson->unit?->subject?->name ?? '—' }} / {{ $lesson->unit?->title ?? '—' }} · ⏱ {{ $lesson->duration_minutes }} {{ __('student.min') }} · 👁 {{ $lesson->view_count }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('teacher.lessons.edit', $lesson) }}" class="btn-outline !py-2 text-xs">✏️ {{ __('app.edit') }}</a>
                <form method="POST" action="{{ route('teacher.lessons.publish', $lesson) }}">@csrf @method('PATCH')
                    <button class="btn-outline !py-2 text-xs">{{ $lesson->status === 'published' ? '📥' : '📤' }}</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📚</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.no_lessons_yet') }}</p>
        <a href="{{ route('teacher.lessons.create') }}" class="btn-primary mt-5 inline-flex">{{ __('teacher.add_lesson') }}</a>
    </div>
    @endforelse

    @if($lessons->hasPages())<div class="flex justify-center">{{ $lessons->links() }}</div>@endif
</div>
@endsection
