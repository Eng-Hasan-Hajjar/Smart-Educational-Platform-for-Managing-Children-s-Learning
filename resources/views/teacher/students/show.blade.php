@extends('layouts.app')
@section('title', $student->name)
@section('page-title', $student->name)
@section('page-subtitle', $student->studentProfile?->academicLevel?->name . ' · ' . ($student->classrooms->first()?->name ?? ''))

@section('content')
<div class="space-y-6">

    {{-- ══════════ Back Button ══════════ --}}
    <div class="flex items-center gap-3 animate-fade-up">
        <a href="{{ route('teacher.students.index') }}" class="btn-outline !py-2 !px-3 text-xs">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back') }}
        </a>
        <a href="{{ route('teacher.reports.student', $student) }}" class="btn-primary !py-2 text-xs">
            📊 {{ __('teacher.view_full_report') }}
        </a>
        <a href="{{ route('messages.compose') }}?recipient_id={{ $student->parents->first()?->id }}"
           class="btn-outline !py-2 text-xs">
            ✉️ {{ __('teacher.contact_parent') }}
        </a>
    </div>

    {{-- ══════════ Header Card ══════════ --}}
    <div class="card animate-fade-up" style="animation-delay:.04s">
        <div class="flex flex-col sm:flex-row items-center gap-5">
            <div class="avatar-ring flex-shrink-0">
                <img src="{{ $student->avatar_url }}" class="w-20 h-20 object-cover" alt="">
            </div>
            <div class="flex-1 text-center sm:text-start">
                <h2 class="text-xl font-extrabold text-main">{{ $student->name }}</h2>
                <p class="text-muted text-sm mt-0.5">{{ $student->email }}</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2">
                    <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }}">
                        {{ $student->studentProfile?->status_label ?? '—' }}
                    </span>
                    @foreach($student->classrooms as $c)
                    <span class="badge-brand">{{ $c->name }}</span>
                    @endforeach
                    @if($student->studentProfile?->student_number)
                    <span class="badge-gray">{{ $student->studentProfile->student_number }}</span>
                    @endif
                </div>
            </div>

            {{-- Level Ring --}}
            @if($student->gamification)
            @php
                $circumference = 2 * 3.14159265 * 38;
                $offset = $circumference * (1 - ($student->gamification->level_progress / 100));
            @endphp
            <div class="relative flex-shrink-0">
                <svg viewBox="0 0 84 84" class="w-20 h-20 -rotate-90">
                    <circle cx="42" cy="42" r="38" fill="none" stroke="var(--border-app)" stroke-width="6"/>
                    <circle cx="42" cy="42" r="38" fill="none" stroke="var(--brand-500)" stroke-width="6"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $offset }}"
                            style="transition: stroke-dashoffset 1s ease"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-lg font-black text-main">{{ $student->gamification->level }}</span>
                    <span class="text-[9px] text-muted -mt-0.5">{{ __('student.level') }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════ Quick Stats ══════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 stagger animate-fade-up" style="animation-delay:.06s">
        @php
            $totalLessons = $progressBySubject->sum('total');
            $doneLessons  = $progressBySubject->sum('completed');
        @endphp
        @foreach([
            ['label'=>__('teacher.lessons_completed'),  'value'=>$doneLessons.'/'.$totalLessons, 'icon'=>'📚','ring'=>'brand'],
            ['label'=>__('teacher.quizzes_taken'),       'value'=>$quizResults->count(),         'icon'=>'📝','ring'=>'info'],
            ['label'=>__('teacher.assignments_done'),    'value'=>$submissions->count(),          'icon'=>'📋','ring'=>'warning'],
            ['label'=>__('teacher.attendance_rate_label'),'value'=>$attendanceRate.'%',           'icon'=>'✅','ring'=>($attendanceRate>=75?'success':'danger')],
        ] as $s)
        <div class="card">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2
                        bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600">
                {{ $s['icon'] }}
            </div>
            <p class="text-xl font-black text-main">{{ $s['value'] }}</p>
            <p class="text-muted text-xs mt-0.5">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ══════════ Progress By Subject ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.08s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📚</span>
                {{ __('teacher.progress_by_subject') }}
            </h3>
            @forelse($progressBySubject as $subjectName => $data)
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1.5">
                    <p class="text-sm font-bold text-main">{{ $subjectName }}</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-muted">{{ $data['time_spent'] }} {{ __('teacher.minutes') }}</span>
                        <span class="text-xs font-black text-brand-500">{{ $data['completed'] }}/{{ $data['total'] }}</span>
                    </div>
                </div>
                <div class="progress-track">
                    <div class="progress-fill"
                         style="width: {{ $data['pct'] }}%"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-6 animate-fade">
                <span class="text-3xl animate-float inline-block">📚</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_progress_data') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Attendance Calendar ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.10s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">📅</span>
                {{ __('teacher.attendance_last_30') }} — {{ $attendanceRate }}%
            </h3>

            <div class="flex flex-wrap gap-1.5 mb-3">
                @forelse($attendances as $att)
                <div title="{{ $att->date->format('d/m') }} — {{ __('status.'.$att->status) }}"
                     class="w-8 h-8 rounded-lg flex items-center justify-center text-sm cursor-default transition hover:scale-110
                            {{ match($att->status) {
                                'present' => 'bg-success-50 text-success-600',
                                'absent'  => 'bg-danger-50 text-danger-600',
                                'late'    => 'bg-warning-50 text-warning-600',
                                default   => 'bg-info-50 text-info-600',
                            } }}">
                    {{ match($att->status) {
                        'present' => '✅',
                        'absent'  => '❌',
                        'late'    => '⏰',
                        default   => '📋',
                    } }}
                </div>
                @empty
                <p class="text-muted text-sm">{{ __('teacher.no_attendance_recorded') }}</p>
                @endforelse
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-3 text-xs text-muted pt-3 border-t border-bd">
                @foreach(['present'=>['success',__('status.present')],'absent'=>['danger',__('status.absent')],'late'=>['warning',__('status.late')],'excused'=>['info',__('status.excused')]] as $k=>[$c,$l])
                <span class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded bg-{{ $c }}-200 inline-block"></span>
                    {{ $l }}
                </span>
                @endforeach
            </div>

            {{-- Rate Bar --}}
            <div class="mt-3">
                <div class="progress-track">
                    <div class="progress-fill !bg-none"
                         style="width: {{ $attendanceRate }}%;
                                background: {{ $attendanceRate >= 75 ? 'var(--success-500)' : 'var(--danger-500)' }}">
                    </div>
                </div>
                @if($attendanceRate < 75)
                <p class="text-xs text-danger-600 font-bold mt-1.5">
                    ⚠️ {{ __('teacher.attendance_below_threshold') }}
                </p>
                @endif
            </div>
        </div>

        {{-- ══════════ Recent Quiz Results ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.12s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">📝</span>
                {{ __('teacher.recent_quiz_results') }}
            </h3>
            @forelse($quizResults as $attempt)
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
        <div class="card animate-fade-up" style="animation-delay:.14s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-success-50 text-success-600 flex items-center justify-center text-base">📋</span>
                {{ __('teacher.assignment_submissions') }}
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
    </div>
</div>
@endsection