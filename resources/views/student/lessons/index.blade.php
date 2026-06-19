@extends('layouts.app')
@section('title', __('student.my_lessons'))
@section('page-title', __('student.my_lessons'))
@section('page-subtitle', __('student.my_lessons_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input ps-10" placeholder="{{ __('student.search_lessons') }}">
            </div>
            <select name="subject_id" class="input sm:w-52">
                <option value="">{{ __('app.subjects') }}</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline">
                🔍 {{ __('app.filter') }}
            </button>
            @if(request()->hasAny(['search','subject_id']))
            <a href="{{ route('student.lessons.index') }}" class="btn-outline text-danger-600 px-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════ Subjects & Units ══════════ --}}
    @forelse($subjects as $subject)
    <div class="card animate-fade-up">

        {{-- Subject Header --}}
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-bd">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0"
                 style="background: {{ $subject->color ?? 'var(--brand-500)' }}1A;
                        color: {{ $subject->color ?? 'var(--brand-500)' }}">
                {{ $subject->icon ?? '📖' }}
            </div>
            <div class="flex-1">
                <h3 class="font-extrabold text-main text-lg">{{ $subject->name }}</h3>
                <p class="text-muted text-xs mt-0.5">{{ $subject->units->count() }} {{ __('student.units') }}</p>
            </div>

            {{-- Overall subject progress --}}
            @php
                $subjectTotal    = $subject->units->sum(fn($u) => $u->publishedLessons->count());
                $subjectCompleted = $subject->units->sum(fn($u) =>
                    $u->publishedLessons->filter(fn($l) =>
                        isset($progressMap[$l->id]) && $progressMap[$l->id] >= 100
                    )->count()
                );
                $subjectPct = $subjectTotal > 0 ? round($subjectCompleted / $subjectTotal * 100) : 0;
            @endphp
            <div class="hidden sm:flex flex-col items-end gap-1 flex-shrink-0">
                <span class="text-xs font-black text-brand-500">{{ $subjectPct }}%</span>
                <div class="w-24 progress-track">
                    <div class="progress-fill" style="width: {{ $subjectPct }}%"></div>
                </div>
                <span class="text-[10px] text-muted">{{ $subjectCompleted }}/{{ $subjectTotal }} {{ __('student.lesson') }}</span>
            </div>
        </div>

        {{-- Units --}}
        <div class="space-y-3">
            @foreach($subject->units as $unit)
            @php
                $totalLessons     = $unit->publishedLessons->count();
                $completedLessons = $unit->publishedLessons->filter(fn($l) =>
                    isset($progressMap[$l->id]) && $progressMap[$l->id] >= 100
                )->count();
                $unitPct = $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0;
            @endphp

            <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }"
                 class="border border-bd rounded-2xl overflow-hidden">

                {{-- Unit Header --}}
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-between p-4 hover:bg-hover transition text-start">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0
                                    {{ $unitPct === 100 ? 'bg-success-50 text-success-600' : 'bg-brand-50 text-brand-600' }}">
                            {{ $unitPct === 100 ? '✅' : $loop->iteration }}
                        </div>
                        <div class="text-start">
                            <p class="font-bold text-main text-sm">{{ $unit->title }}</p>
                            <p class="text-xs text-muted mt-0.5">
                                {{ $completedLessons }}/{{ $totalLessons }} {{ __('student.lessons_done') }}
                                @if($unit->duration_weeks) · {{ $unit->duration_weeks }} {{ __('student.weeks') }} @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="hidden sm:flex items-center gap-2">
                            <div class="w-20 progress-track">
                                <div class="progress-fill" style="width: {{ $unitPct }}%"></div>
                            </div>
                            <span class="text-xs font-black text-brand-500 w-9">{{ $unitPct }}%</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-faint transition-transform duration-300"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Lessons --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="border-t border-bd bg-surface2/50 p-3 space-y-2">

                    @forelse($unit->publishedLessons as $lesson)
                    @php
                        $pct  = $progressMap[$lesson->id] ?? 0;
                        $done = $pct >= 100;
                    @endphp
                    <a href="{{ route('student.lessons.show', $lesson) }}"
                       class="flex items-center gap-3 p-3 rounded-2xl bg-surface border border-bd
                              hover:border-brand-400 hover:shadow-glow transition group">

                        {{-- Status Icon --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0 transition-transform group-hover:scale-105
                                    {{ $done ? 'bg-success-50 text-success-600' : ($pct > 0 ? 'bg-warning-50 text-warning-600' : 'bg-hover text-faint') }}">
                            {{ $done ? '✅' : ($pct > 0 ? '▶️' : '🔓') }}
                        </div>

                        {{-- Thumbnail --}}
                        <img src="{{ $lesson->thumbnail_url }}"
                             class="w-10 h-10 rounded-xl object-cover flex-shrink-0 hidden sm:block" alt="">

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-main truncate group-hover:text-brand-500 transition">
                                {{ $lesson->title }}
                            </p>
                            <div class="flex items-center gap-3 text-xs text-faint mt-0.5">
                                <span>⏱ {{ $lesson->duration_minutes }} {{ __('student.min') }}</span>
                                <span>👁 {{ number_format($lesson->view_count) }}</span>
                                @if($lesson->is_free)
                                <span class="badge-info">{{ __('teacher.free') }}</span>
                                @endif
                            </div>

                            @if($pct > 0 && !$done)
                            <div class="progress-track mt-1.5">
                                <div class="progress-fill" style="width: {{ $pct }}%"></div>
                            </div>
                            @endif
                        </div>

                        {{-- Progress % --}}
                        <div class="flex-shrink-0 text-end">
                            @if($done)
                            <span class="badge-green">{{ __('student.completed') }}</span>
                            @elseif($pct > 0)
                            <span class="text-brand-500 font-black text-sm">{{ $pct }}%</span>
                            @else
                            <svg class="w-4 h-4 text-faint flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-4 text-muted text-sm">
                        {{ __('student.no_lessons_in_unit') }}
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📚</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('student.no_subjects_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('student.no_subjects_hint') }}</p>
    </div>
    @endforelse
</div>
@endsection