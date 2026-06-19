@extends('layouts.app')
@section('title', __('teacher.student_report') . ' — ' . $student->name)
@section('page-title', __('teacher.student_report'))
@section('page-subtitle', $student->name . ' · ' . ($student->classrooms->first()?->name ?? ''))

@section('content')
<div class="space-y-6">

    {{-- ══════════ Back + Actions ══════════ --}}
    <div class="flex items-center gap-3 animate-fade-up">
        <a href="{{ route('teacher.reports.index') }}" class="btn-outline !py-2 !px-3 text-xs">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back') }}
        </a>
        <a href="{{ route('teacher.students.show', $student) }}" class="btn-outline !py-2 text-xs">
            👤 {{ __('teacher.view_student_profile') }}
        </a>
    </div>

    {{-- ══════════ Header ══════════ --}}
    <div class="card animate-fade-up" style="animation-delay:.04s">
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="avatar-ring flex-shrink-0">
                <img src="{{ $student->avatar_url }}" class="w-16 h-16 object-cover" alt="">
            </div>
            <div class="flex-1 text-center sm:text-start">
                <h2 class="text-xl font-extrabold text-main">{{ $student->name }}</h2>
                <p class="text-muted text-sm mt-0.5">
                    {{ $student->studentProfile?->academicLevel?->name ?? '—' }}
                    @if($student->classrooms->first()) · {{ $student->classrooms->first()->name }} @endif
                </p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                    <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }}">
                        {{ $student->studentProfile?->status_label ?? '—' }}
                    </span>
                    @if($student->gamification)
                    <span class="badge-brand">
                        🏆 {{ $student->gamification->level_title }}
                        · {{ number_format($student->gamification->total_points) }} {{ __('student.pts') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ══════════ Lesson Progress ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.06s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📚</span>
                {{ __('teacher.lesson_progress') }}
            </h3>
            @forelse($progress as $p)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0
                            {{ $p->is_completed ? 'bg-success-50 text-success-600' : 'bg-warning-50 text-warning-600' }}">
                    {{ $p->is_completed ? '✅' : '▶️' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-main truncate">{{ $p->lesson->title }}</p>
                    <p class="text-xs text-muted">{{ $p->lesson->unit?->subject?->name ?? '—' }}</p>
                    <div class="progress-track mt-1.5">
                        <div class="progress-fill" style="width: {{ $p->progress_percentage }}%"></div>
                    </div>
                </div>
                <span class="text-xs font-black {{ $p->is_completed ? 'text-success-600' : 'text-brand-500' }} flex-shrink-0">
                    {{ $p->progress_percentage }}%
                </span>
            </div>
            @empty
            <div class="text-center py-6 animate-fade">
                <span class="text-3xl animate-float inline-block">📚</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_lesson_progress') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Quiz Results ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.08s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">📝</span>
                {{ __('teacher.quiz_results') }}
            </h3>

            @if($quizAttempts->count())
            {{-- Average Score --}}
            @php $avgPct = round($quizAttempts->avg('percentage'), 1); @endphp
            <div class="mb-4 p-3 rounded-2xl bg-surface2 border border-bd">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs text-muted font-semibold">{{ __('teacher.avg_score_label') }}</span>
                    <span class="font-black text-brand-500">{{ $avgPct }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill !bg-none"
                         style="width:{{ $avgPct }}%;
                                background: {{ $avgPct >= 80 ? 'var(--success-500)' : ($avgPct >= 60 ? 'var(--warning-500)' : 'var(--danger-500)') }}">
                    </div>
                </div>
            </div>
            @endif

            @forelse($quizAttempts as $attempt)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2">
                <span class="text-lg flex-shrink-0">{{ $attempt->is_passed ? '✅' : '❌' }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-main truncate">{{ $attempt->quiz->title }}</p>
                    <p class="text-xs text-muted">{{ $attempt->quiz->subject?->name ?? '—' }} · {{ $attempt->submitted_at?->format('d/m/Y') }}</p>
                </div>
                <div class="flex-shrink-0 text-end">
                    <p class="font-black text-sm {{ $attempt->is_passed ? 'text-success-600' : 'text-danger-600' }}">
                        {{ round($attempt->percentage) }}%
                    </p>
                    <p class="text-xs text-muted">{{ $attempt->total_marks_obtained }}/{{ $attempt->quiz->total_marks }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6 animate-fade">
                <span class="text-3xl animate-float inline-block">📝</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_quiz_results') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Assignment Submissions ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.10s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-success-50 text-success-600 flex items-center justify-center text-base">📋</span>
                {{ __('teacher.assignment_history') }}
            </h3>
            @forelse($submissions as $sub)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2">
                <span class="text-lg flex-shrink-0">
                    {{ $sub->marks_obtained !== null ? '✅' : '⏳' }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-main truncate">{{ $sub->assignment->title }}</p>
                    <p class="text-xs text-muted">
                        {{ $sub->assignment->subject?->name ?? '—' }} · {{ $sub->created_at->format('d/m/Y') }}
                        @if($sub->is_late) <span class="text-danger-600 font-bold">· {{ __('teacher.late_label') }}</span> @endif
                    </p>
                </div>
                <div class="flex-shrink-0 text-end">
                    @if($sub->marks_obtained !== null)
                    <p class="font-black text-sm text-success-600">
                        {{ $sub->marks_obtained }}/{{ $sub->assignment->total_marks }}
                    </p>
                    @else
                    <span class="badge-yellow">{{ __('teacher.pending_grade') }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-6 animate-fade">
                <span class="text-3xl animate-float inline-block">📋</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_submissions') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Attendance ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.12s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">📅</span>
                {{ __('teacher.attendance_last_30') }}
            </h3>
            <div class="flex flex-wrap gap-1.5 mb-3">
                @forelse($attendances as $att)
                <div title="{{ $att->date->format('d/m') }} — {{ __('status.'.$att->status) }}"
                     class="w-8 h-8 rounded-lg flex items-center justify-center text-sm hover:scale-110 transition cursor-default
                            {{ match($att->status) {
                                'present' => 'bg-success-50 text-success-600',
                                'absent'  => 'bg-danger-50 text-danger-600',
                                'late'    => 'bg-warning-50 text-warning-600',
                                default   => 'bg-info-50 text-info-600',
                            } }}">
                    {{ match($att->status) { 'present' => '✅', 'absent' => '❌', 'late' => '⏰', default => '📋' } }}
                </div>
                @empty
                <p class="text-muted text-sm">{{ __('teacher.no_attendance_recorded') }}</p>
                @endforelse
            </div>
            @if($attendances->count())
            @php
                $rate = round($attendances->where('status','present')->count() / $attendances->count() * 100, 1);
            @endphp
            <div class="pt-3 border-t border-bd">
                <div class="flex items-center justify-between mb-1.5 text-xs">
                    <span class="text-muted">{{ __('teacher.attendance_rate') }}</span>
                    <span class="font-black {{ $rate >= 75 ? 'text-success-600' : 'text-danger-600' }}">{{ $rate }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill !bg-none"
                         style="width: {{ $rate }}%;
                                background: {{ $rate >= 75 ? 'var(--success-500)' : 'var(--danger-500)' }}">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection