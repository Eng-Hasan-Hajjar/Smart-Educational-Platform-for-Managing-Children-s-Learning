@extends('layouts.app')
@section('title', __('parent.view_schedule') . ' — ' . $student->name)
@section('page-title', __('app.schedule'))
@section('page-subtitle', $student->name . ' · ' . ($student->classrooms->first()?->name ?? ''))

@section('content')
<div class="space-y-5">

    <a href="{{ route('parent.children.show', $student) }}" class="btn-outline !py-2 !px-3 text-xs inline-flex animate-fade-up">
        <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('app.back') }}
    </a>

    {{-- ══════════ Weekly Schedule Grid ══════════ --}}
    <div class="overflow-x-auto animate-fade-up" style="animation-delay:.04s">
        <div class="grid grid-cols-5 gap-3 min-w-[800px]">
            @foreach([0=>'sunday',1=>'monday',2=>'tuesday',3=>'wednesday',4=>'thursday'] as $day=>$key)
            <div class="card !p-3">
                <h4 class="font-bold text-main text-center mb-3 pb-2.5 border-b border-bd text-sm">
                    {{ __('app.'.$key) }}
                </h4>
                <div class="space-y-2">
                    @forelse($schedules->get($day, collect()) as $schedule)
                    <div class="p-3 rounded-2xl text-xs border transition hover:shadow-glow"
                         style="background: {{ $schedule->subject->color ?? 'var(--brand-500)' }}14;
                                border-color: {{ $schedule->subject->color ?? 'var(--brand-500)' }}30">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span>{{ $schedule->subject->icon ?? '📖' }}</span>
                            <p class="font-bold text-main">{{ $schedule->subject->name }}</p>
                        </div>
                        <p class="text-muted">⏱ {{ $schedule->timeSlot->start_time }} – {{ $schedule->timeSlot->end_time }}</p>
                        <p class="text-faint truncate">👨‍🏫 {{ $schedule->teacher->name }}</p>
                        @if($schedule->room)
                        <p class="text-faint">🚪 {{ $schedule->room }}</p>
                        @endif
                    </div>
                    @empty
                    <p class="text-center text-faint text-xs py-6">—</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection