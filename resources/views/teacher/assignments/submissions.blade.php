@extends('layouts.app')
@section('title', __('teacher.submissions') . ' — ' . $assignment->title)
@section('page-title', __('teacher.submissions'))
@section('page-subtitle', $assignment->title . ' · ' . $assignment->subject->name . ' · ' . $assignment->classroom->name)

@section('content')
<div class="space-y-5">

    {{-- ══════════ Summary Card ══════════ --}}
    <div class="card animate-fade-up">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $totalStudents  = $assignment->classroom->students()->count();
                $submittedCount = $submissions->total();
                $gradedCount    = $submissions->getCollection()->where('status', 'graded')->count();
                $lateCount      = $submissions->getCollection()->where('is_late', true)->count();
                $submitPct      = $totalStudents > 0 ? round($submittedCount / $totalStudents * 100) : 0;
            @endphp
            @foreach([
                ['label'=>__('teacher.total_students'), 'value'=>$totalStudents, 'icon'=>'👥', 'ring'=>'brand'],
                ['label'=>__('teacher.submitted'),      'value'=>$submittedCount,'icon'=>'📤','ring'=>'info'],
                ['label'=>__('teacher.graded'),         'value'=>$gradedCount,   'icon'=>'✅', 'ring'=>'success'],
                ['label'=>__('teacher.late_label'),     'value'=>$lateCount,     'icon'=>'⌛', 'ring'=>'warning'],
            ] as $s)
            <div class="text-center">
                <div class="w-11 h-11 rounded-2xl mx-auto mb-2 flex items-center justify-center text-xl
                            bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600">
                    {{ $s['icon'] }}
                </div>
                <p class="text-2xl font-black text-main">{{ $s['value'] }}</p>
                <p class="text-muted text-xs mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Progress Bar --}}
        <div class="mt-4 pt-4 border-t border-bd">
            <div class="flex items-center justify-between text-xs text-muted mb-1.5">
                <span>{{ __('teacher.submission_progress') }}</span>
                <span class="font-bold text-brand-500">{{ $submitPct }}%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $submitPct }}%"></div>
            </div>
        </div>

        {{-- Due Date Alert --}}
        @if($assignment->isOverdue())
        <div class="mt-4 p-3 rounded-2xl bg-danger-50 border border-danger-500/25 flex items-center gap-3">
            <span class="text-xl">⌛</span>
            <p class="text-sm text-danger-600 font-medium">
                {{ __('teacher.assignment_overdue_note', ['date' => $assignment->due_date->format('d/m/Y H:i')]) }}
            </p>
        </div>
        @else
        <div class="mt-4 p-3 rounded-2xl bg-info-50 border border-info-500/25 flex items-center gap-3">
            <span class="text-xl">📅</span>
            <p class="text-sm text-info-600 font-medium">
                {{ __('teacher.due') }}: {{ $assignment->due_date->format('d/m/Y - H:i') }}
                ({{ $assignment->due_date->diffForHumans() }})
            </p>
        </div>
        @endif
    </div>

    {{-- ══════════ Submissions List ══════════ --}}
    @forelse($submissions as $sub)
    <div class="card animate-fade-up" style="animation-delay:{{ .04 + $loop->index * .03 }}s">
        <div class="flex items-start gap-4">

            {{-- Student Avatar --}}
            <img src="{{ $sub->student->avatar_url }}"
                 class="w-12 h-12 rounded-2xl object-cover flex-shrink-0 ring-2 ring-bd" alt="">

            <div class="flex-1 min-w-0">

                {{-- Header --}}
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div>
                        <p class="font-bold text-main">{{ $sub->student->name }}</p>
                        <p class="text-xs text-muted mt-0.5">
                            {{ __('teacher.submitted_at') }}: {{ $sub->created_at->format('d/m/Y H:i') }}
                            @if($sub->is_late)
                            <span class="badge-red ms-2">{{ __('teacher.late_label') }}</span>
                            @endif
                        </p>
                    </div>
                    <span class="badge-{{ $sub->status === 'graded' ? 'green' : 'yellow' }} flex-shrink-0">
                        {{ $sub->status === 'graded' ? __('teacher.graded') : __('teacher.pending_grade') }}
                    </span>
                </div>

                {{-- Text Answer --}}
                @if($sub->text_answer)
                <div class="mt-3 p-3.5 rounded-2xl bg-surface2 border border-bd">
                    <p class="text-xs font-bold text-muted mb-1.5">📝 {{ __('teacher.text_answer') }}</p>
                    <p class="text-sm text-main leading-relaxed">{{ Str::limit($sub->text_answer, 300) }}</p>
                    @if(Str::length($sub->text_answer) > 300)
                    <button x-data="{ expanded: false }" @click="expanded = !expanded"
                            class="text-xs text-brand-500 font-bold mt-1 hover:underline">
                        <span x-show="!expanded">{{ __('teacher.show_full') }}</span>
                        <span x-show="expanded" x-cloak>{{ __('teacher.show_less') }}</span>
                    </button>
                    @endif
                </div>
                @endif

                {{-- File Attachment --}}
                @if($sub->file_path)
                <div class="mt-3 flex items-center gap-3 p-3 rounded-2xl bg-surface2 border border-bd">
                    <div class="w-10 h-10 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-xl flex-shrink-0">
                        📎
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-main truncate">{{ $sub->file_name }}</p>
                        <p class="text-xs text-muted">{{ $sub->file_size_human }}</p>
                    </div>
                    <a href="{{ $sub->file_url }}" target="_blank" download
                       class="btn-outline !py-1.5 !px-3 text-xs flex-shrink-0">
                        ⬇️ {{ __('teacher.download') }}
                    </a>
                </div>
                @endif

                {{-- Grading Result (if already graded) --}}
                @if($sub->status === 'graded')
                <div class="mt-3 p-4 rounded-2xl bg-success-50 border border-success-500/25">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-bold text-success-600">
                            ✅ {{ __('teacher.grade') }}: {{ $sub->marks_obtained }}/{{ $assignment->total_marks }}
                            ({{ round($sub->marks_obtained / $assignment->total_marks * 100) }}%)
                        </p>
                        <span class="text-xs text-muted">{{ $sub->graded_at?->format('d/m/Y') }}</span>
                    </div>
                    @if($sub->teacher_feedback)
                    <p class="text-xs text-main bg-white/60 rounded-xl p-2.5">{{ $sub->teacher_feedback }}</p>
                    @endif
                </div>
                @endif

                {{-- Grading Form (if not graded) --}}
                @if($sub->status !== 'graded')
                <form method="POST"
                      action="{{ route('teacher.assignments.grade', [$assignment, $sub]) }}"
                      class="mt-4 pt-4 border-t border-bd"
                      x-data="{ loading: false }" @submit="loading = true">
                    @csrf

                    <p class="text-xs font-bold text-muted uppercase tracking-widest mb-3">
                        {{ __('teacher.grade_submission') }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="label">
                                {{ __('teacher.marks_obtained') }}
                                <span class="text-faint font-normal">/ {{ $assignment->total_marks }}</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="marks_obtained" required
                                       min="0" max="{{ $assignment->total_marks }}"
                                       step="0.5" class="input pe-16"
                                       placeholder="0">
                                <span class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-faint text-sm pointer-events-none">
                                    /{{ $assignment->total_marks }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="label">{{ __('teacher.teacher_feedback') }}</label>
                            <input type="text" name="teacher_feedback" class="input"
                                   placeholder="{{ __('teacher.feedback_placeholder') }}">
                        </div>
                    </div>

                    <div class="flex justify-end mt-3">
                        <button type="submit" :disabled="loading" class="btn-primary">
                            <span x-show="!loading" class="flex items-center gap-1.5">
                                ✅ {{ __('teacher.submit_grade') }}
                            </span>
                            <span x-show="loading" x-cloak class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                {{ __('app.loading') }}
                            </span>
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📭</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.no_submissions_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_submissions_hint') }}</p>
    </div>
    @endforelse

    @if($submissions->hasPages())
    <div class="flex justify-center animate-fade-up">
        {{ $submissions->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection