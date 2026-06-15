@extends('layouts.app')
@section('title', __('teacher.welcome'))
@section('page-title', __('app.dashboard'))

@php
    $hour = now()->hour;
    $greetingKey = match(true) {
        $hour < 12 => 'greeting_morning',
        $hour < 17 => 'greeting_afternoon',
        $hour < 21 => 'greeting_evening',
        default    => 'greeting_night',
    };
    $dayName = strtolower(now()->format('l'));
@endphp

@section('content')
<div class="space-y-6">

    {{-- ══════════ Welcome Banner ══════════ --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white animate-fade-up"
         style="background: linear-gradient(135deg, var(--brand-600), var(--brand-700) 65%, var(--bg-sidebar-to))">

        {{-- توهجات زخرفية --}}
        <div class="absolute w-64 h-64 rounded-full bg-accent-400/20 blur-3xl -top-16 end-[-3rem] animate-pulse-glow"></div>
        <div class="absolute w-72 h-72 rounded-full bg-brand-400/15 blur-3xl -bottom-20 start-[-4rem] animate-pulse-glow" style="animation-delay:1s"></div>
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>

        {{-- أيقونات عائمة --}}
        <div class="absolute top-8 end-1/4 text-3xl animate-float opacity-30 hidden sm:block">📐</div>
        <div class="absolute bottom-10 end-12 text-2xl animate-float opacity-30 hidden sm:block" style="animation-delay:.8s">✏️</div>
        <div class="absolute top-1/2 end-1/2 text-2xl animate-float opacity-20 hidden lg:block" style="animation-delay:1.4s">📊</div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">

            <div class="avatar-ring flex-shrink-0 animate-scale-in !p-[3px]">
                <img src="{{ auth()->user()->avatar_url }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover" alt="">
            </div>

            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/65 text-sm">{{ __('app.'.$greetingKey) }} 👋</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">{{ auth()->user()->name }}</h2>
                <p class="text-white/60 text-sm mt-1">
                    {{ __('teacher.ready_subtitle') }}
                    · {{ __('app.'.$dayName) }}, {{ now()->format('d/m/Y') }}
                </p>
            </div>

            <div class="hidden lg:flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-5xl animate-float">👨‍🏫</span>
            </div>
        </div>
    </div>

    {{-- ══════════ Quick Stats ══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 stagger">
        @foreach([
            ['label' => __('teacher.total_lessons'),     'value' => $stats['lessons'],             'icon' => '🎬', 'ring' => 'brand'],
            ['label' => __('teacher.my_students'),       'value' => $stats['students'],            'icon' => '👨‍🎓', 'ring' => 'info'],
            ['label' => __('teacher.total_assignments'), 'value' => $stats['assignments'],         'icon' => '📋', 'ring' => 'warning'],
            ['label' => __('teacher.total_quizzes'),     'value' => $stats['quizzes'],             'icon' => '📝', 'ring' => 'success'],
            ['label' => __('teacher.pending_grading'),   'value' => $stats['pending_submissions'], 'icon' => '⏳', 'ring' => 'danger'],
        ] as $s)
        <div class="card card-hover">
            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3
                        bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600">
                {{ $s['icon'] }}
            </div>
            <p class="text-2xl sm:text-3xl font-black text-main"
               x-data="{ display: 0 }"
               x-init="
                    let target = {{ $s['value'] }}, start=null, dur=900;
                    function step(ts){ if(!start) start=ts;
                        let p=Math.min((ts-start)/dur,1);
                        display = Math.floor(p*target);
                        if(p<1) requestAnimationFrame(step); else display=target;
                    }
                    requestAnimationFrame(step);
               "
               x-text="display"></p>
            <p class="text-muted text-xs mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ══════════ Today's Schedule ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.05s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">📅</span>
                    {{ __('teacher.todays_classes') }}
                </h3>
            </div>

            @forelse($todaySchedules as $schedule)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2 animate-slide">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                     style="background: {{ $schedule->subject->color ?? 'var(--brand-500)' }}1A; color: {{ $schedule->subject->color ?? 'var(--brand-500)' }}">
                    {{ $schedule->subject->icon ?? '📖' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate">{{ $schedule->subject->name }}</p>
                    <p class="text-xs text-muted">
                        {{ $schedule->classroom->name }}
                        · {{ $schedule->timeSlot->start_time }} – {{ $schedule->timeSlot->end_time }}
                        @if($schedule->room) · {{ __('teacher.room') }} {{ $schedule->room }} @endif
                    </p>
                </div>
                @if($schedule->is_online)
                <a href="{{ $schedule->meeting_link }}" target="_blank"
                   class="badge-green flex-shrink-0 hover:opacity-80 transition">
                    🔗 {{ __('teacher.go_to_room') }}
                </a>
                @endif
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">☕</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_classes_today') }}</p>
                <p class="text-faint text-xs">{{ __('teacher.enjoy_your_day') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Recent Lessons ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">🎬</span>
                    {{ __('teacher.recent_lessons') }}
                </h3>
                <a href="{{ route('teacher.lessons.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    {{ __('app.view_all') }}
                </a>
            </div>

            @forelse($recentLessons as $lesson)
            <a href="{{ route('teacher.lessons.edit', $lesson) }}"
               class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:border-brand-400 hover:shadow-glow transition mb-2 group">
                <img src="{{ $lesson->thumbnail_url }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate group-hover:text-brand-500 transition">{{ $lesson->title }}</p>
                    <p class="text-xs text-muted">{{ $lesson->unit->subject->name ?? '' }} · 👁 {{ number_format($lesson->view_count) }} {{ __('teacher.views') }}</p>
                </div>
                <span class="badge-{{ $lesson->status === 'published' ? 'green' : 'gray' }} flex-shrink-0">
                    {{ __('status.'.$lesson->status) }}
                </span>
            </a>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">🎬</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_lessons_yet') }}</p>
                <a href="{{ route('teacher.lessons.create') }}" class="btn-primary mt-3 !py-2 !px-4 text-xs">
                    {{ __('teacher.create_first_lesson') }}
                </a>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Submissions to Grade ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.15s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-danger-50 text-danger-600 flex items-center justify-center text-base">⏳</span>
                    {{ __('teacher.submissions_to_grade') }}
                </h3>
            </div>

            @forelse($recentSubmissions as $sub)
            <a href="{{ route('teacher.assignments.submissions', $sub->assignment) }}"
               class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2 group">
                <img src="{{ $sub->student->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate group-hover:text-brand-500 transition">{{ $sub->student->name }}</p>
                    <p class="text-xs text-muted truncate">{{ $sub->assignment->title }}</p>
                </div>
                <div class="text-end flex-shrink-0">
                    <span class="badge-yellow">{{ __('teacher.grade_now') }}</span>
                    <p class="text-xs text-faint mt-1">{{ $sub->created_at->diffForHumans() }}</p>
                </div>
            </a>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">✅</span>
                <p class="text-muted text-sm mt-2">{{ __('teacher.no_pending_submissions') }}</p>
                <p class="text-faint text-xs">{{ __('teacher.all_graded') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Quick Actions ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.2s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">⚡</span>
                    {{ __('teacher.quick_actions') }}
                </h3>
            </div>

            <div class="grid grid-cols-2 gap-3 stagger">
                <a href="{{ route('teacher.lessons.create') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-brand-50 hover:bg-brand-100 hover:-translate-y-1 transition-all text-brand-600">
                    <span class="text-3xl">🎬</span>
                    <span class="text-sm font-bold">{{ __('teacher.new_lesson') }}</span>
                </a>
                <a href="{{ route('teacher.quizzes.create') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-info-50 hover:bg-info-50/70 hover:-translate-y-1 transition-all text-info-600">
                    <span class="text-3xl">📝</span>
                    <span class="text-sm font-bold">{{ __('teacher.new_quiz') }}</span>
                </a>
                <a href="{{ route('teacher.assignments.create') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-warning-50 hover:bg-warning-50/70 hover:-translate-y-1 transition-all text-warning-600">
                    <span class="text-3xl">📋</span>
                    <span class="text-sm font-bold">{{ __('teacher.new_assignment') }}</span>
                </a>
                <a href="{{ route('teacher.attendance.index') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-success-50 hover:bg-success-50/70 hover:-translate-y-1 transition-all text-success-600">
                    <span class="text-3xl">✅</span>
                    <span class="text-sm font-bold">{{ __('teacher.take_attendance') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection