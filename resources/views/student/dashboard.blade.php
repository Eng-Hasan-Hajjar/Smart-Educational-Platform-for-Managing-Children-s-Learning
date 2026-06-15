@extends('layouts.app')
@section('title', __('student.welcome'))
@section('page-title', __('app.dashboard'))

@php
    $hour = now()->hour;
    $greetingKey = match(true) {
        $hour < 12 => 'greeting_morning',
        $hour < 17 => 'greeting_afternoon',
        $hour < 21 => 'greeting_evening',
        default    => 'greeting_night',
    };
    $circumference = 2 * 3.14159265 * 46;
    $offset = $circumference * (1 - ($stats['level_progress'] / 100));
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
        <div class="absolute top-6 end-1/3 text-3xl animate-float opacity-30 hidden sm:block">✨</div>
        <div class="absolute bottom-8 end-12 text-2xl animate-float opacity-30 hidden sm:block" style="animation-delay:.8s">📘</div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">

            {{-- Avatar --}}
            <div class="avatar-ring flex-shrink-0 animate-scale-in !p-[3px]">
                <img src="{{ $student->avatar_url }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover" alt="">
            </div>

            {{-- Greeting --}}
            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/65 text-sm">{{ __('app.'.$greetingKey) }} 👋</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">{{ $student->name }}</h2>
                <p class="text-white/60 text-sm mt-1">
                    {{ $profile?->academicLevel?->name ?? __('app.student') }}
                    · {{ __('app.'.$dayName) }}, {{ now()->format('d/m/Y') }}
                </p>
            </div>

            {{-- Level Ring --}}
            <div class="flex-shrink-0 relative animate-scale-in" style="animation-delay:.1s">
                <svg viewBox="0 0 100 100" class="w-24 h-24 sm:w-28 sm:h-28 -rotate-90">
                    <circle cx="50" cy="50" r="46" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="7"/>
                    <circle cx="50" cy="50" r="46" fill="none" stroke="var(--accent-400)" stroke-width="7"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $offset }}"
                            style="transition: stroke-dashoffset 1.2s cubic-bezier(.22,1,.36,1)"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-black">{{ $stats['level'] }}</span>
                    <span class="text-[10px] text-white/70 -mt-0.5">{{ __('student.level') }}</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 mt-5">
            <div class="flex items-center justify-between text-xs text-white/70 mb-1.5">
                <span class="font-bold">{{ $stats['level_title'] }}</span>
                <span>{{ __('student.progress_to_next_level') }} — {{ $stats['level_progress'] }}%</span>
            </div>
            <div class="w-full bg-white/15 rounded-full h-2.5 overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-accent-400 to-accent-500 transition-all duration-1000"
                     style="width: {{ $stats['level_progress'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- ══════════ Quick Stats ══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 stagger">
        @foreach([
            ['label' => __('student.lessons_completed'), 'value' => $stats['lessons_done'], 'icon' => '📚', 'ring' => 'success'],
            ['label' => __('student.quizzes_taken'),      'value' => $stats['quizzes_done'], 'icon' => '📝', 'ring' => 'info'],
            ['label' => __('student.average_score'),      'value' => $stats['avg_score'],    'icon' => '⭐', 'ring' => 'warning', 'suffix' => '%'],
            ['label' => __('student.total_points'),       'value' => $stats['total_points'], 'icon' => '🏆', 'ring' => 'brand'],
        ] as $s)
        <div class="card card-hover">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl
                            bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600">
                    {{ $s['icon'] }}
                </div>
            </div>
            <p class="text-2xl sm:text-3xl font-black text-main"
               x-data="{ display: 0 }"
               x-init="
                    let target = {{ $s['value'] }}, start=null, dur=900;
                    function step(ts){ if(!start) start=ts;
                        let p=Math.min((ts-start)/dur,1);
                        display = ('{{ $s['suffix'] ?? '' }}'==='%') ? (p*target).toFixed(1) : Math.floor(p*target);
                        if(p<1) requestAnimationFrame(step); else display=target;
                    }
                    requestAnimationFrame(step);
               "
               x-text="display + '{{ $s['suffix'] ?? '' }}'"></p>
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
                    {{ __('student.todays_schedule') }}
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
                    <p class="text-xs text-muted">{{ $schedule->timeSlot->start_time }} – {{ $schedule->timeSlot->end_time }}</p>
                </div>
                @if($schedule->is_online)
                <a href="{{ $schedule->meeting_link }}" target="_blank"
                   class="badge-green flex-shrink-0 hover:opacity-80 transition">
                    🔗 {{ __('student.join_now') }}
                </a>
                @endif
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">🎉</span>
                <p class="text-muted text-sm mt-2">{{ __('student.no_classes_today') }}</p>
                <p class="text-faint text-xs">{{ __('student.enjoy_your_day') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Continue Learning ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">▶️</span>
                    {{ __('student.continue_learning') }}
                </h3>
                <a href="{{ route('student.lessons.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    {{ __('app.view_all') }}
                </a>
            </div>

            @forelse($inProgressLessons as $p)
            <a href="{{ route('student.lessons.show', $p->lesson) }}"
               class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:border-brand-400 hover:shadow-glow transition mb-2 group">
                <img src="{{ $p->lesson->thumbnail_url }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate group-hover:text-brand-500 transition">{{ $p->lesson->title }}</p>
                    <p class="text-xs text-muted mb-1.5">{{ $p->lesson->unit->subject->name ?? '' }}</p>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $p->progress_percentage }}%"></div>
                    </div>
                </div>
                <span class="text-brand-500 font-black text-sm flex-shrink-0">{{ $p->progress_percentage }}%</span>
            </a>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">📚</span>
                <p class="text-muted text-sm mt-2">{{ __('student.no_lessons_progress') }}</p>
                <a href="{{ route('student.lessons.index') }}" class="btn-primary mt-3 !py-2 !px-4 text-xs">
                    {{ __('student.start_learning') }}
                </a>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Pending Assignments ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.15s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">📋</span>
                    {{ __('student.pending_assignments') }}
                </h3>
                <a href="{{ route('student.assignments.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    {{ __('app.view_all') }}
                </a>
            </div>

            @forelse($pendingAssignments as $a)
            @php $overdue = $a->isOverdue(); @endphp
            <div class="flex items-center gap-3 p-3 rounded-2xl border mb-2 transition
                        {{ $overdue ? 'border-danger-500/30 bg-danger-50' : 'border-bd hover:bg-hover' }}">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                            {{ $overdue ? 'bg-danger-500/15 text-danger-600' : 'bg-warning-50 text-warning-600' }}">
                    {{ $overdue ? '⚠️' : '📋' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate">{{ $a->title }}</p>
                    <p class="text-xs text-muted">{{ $a->subject->name }}</p>
                </div>
                <div class="text-end flex-shrink-0">
                    <span class="badge-{{ $overdue ? 'red' : 'yellow' }}">
                        {{ $overdue ? __('student.overdue') : __('student.due') }}
                    </span>
                    <p class="text-xs text-faint mt-1">{{ $a->due_date->format('d/m') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">✅</span>
                <p class="text-muted text-sm mt-2">{{ __('student.no_pending_assignments') }}</p>
                <p class="text-faint text-xs">{{ __('student.all_caught_up') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ AI Recommendations ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.2s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">🤖</span>
                    {{ __('student.ai_recommendations') }}
                </h3>
            </div>

            @forelse($recommendations as $rec)
            @php
                $icon  = match($rec->type) { 'lesson' => '📖', 'quiz' => '📝', 'warning' => '⚠️', default => '✨' };
                $color = match($rec->type) { 'warning' => 'danger', 'quiz' => 'info', default => 'brand' };
                $label = match($rec->type) { 'lesson' => __('student.rec_lesson'), 'quiz' => __('student.rec_quiz'), 'warning' => __('student.rec_warning'), default => '' };
                $link  = match(true) {
                    $rec->type === 'lesson' && $rec->recommendable => route('student.lessons.show', $rec->recommendable_id),
                    $rec->type === 'quiz'   && $rec->recommendable => route('student.quizzes.show', $rec->recommendable_id),
                    default => null,
                };
            @endphp
            <div class="flex items-start gap-3 p-3 rounded-2xl border mb-2 transition
                        {{ $rec->type === 'warning' ? 'border-danger-500/30 bg-danger-50' : 'border-brand-400/20 bg-brand-50' }}">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0
                            bg-{{ $color }}-500/15 text-{{ $color }}-600">
                    {{ $icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-{{ $color }}-600 mb-0.5">{{ $label }}</p>
                    <p class="text-sm text-main leading-relaxed">{{ $rec->reason }}</p>
                    @if($link)
                    <a href="{{ $link }}" class="inline-flex items-center gap-1 text-xs font-bold text-brand-500 hover:text-brand-700 transition mt-1.5">
                        {{ $rec->type === 'quiz' ? __('student.take_quiz') : __('student.view_lesson') }}
                        <svg class="w-3.5 h-3.5 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">✨</span>
                <p class="text-muted text-sm mt-2">{{ __('student.no_recommendations') }}</p>
                <p class="text-faint text-xs">{{ __('student.check_back_later') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════ Recent Badges ══════════ --}}
    <div class="card animate-fade-up" style="animation-delay:.25s">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">🏅</span>
                {{ __('student.recent_badges') }}
            </h3>
            <a href="{{ route('student.achievements.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                {{ __('app.view_all') }}
            </a>
        </div>

        @if($recentBadges->count())
        <div class="flex flex-wrap gap-4 stagger">
            @foreach($recentBadges as $badge)
            <div class="flex flex-col items-center gap-2 group">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl
                            shadow-glow-strong transition-transform group-hover:scale-110 group-hover:rotate-3"
                     style="background: linear-gradient(135deg, {{ $badge->color }}33, {{ $badge->color }}14); border: 2px solid {{ $badge->color }}40">
                    {{ $badge->icon }}
                </div>
                <p class="text-xs font-bold text-main text-center max-w-[80px] truncate">{{ $badge->name }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 animate-fade">
            <span class="text-4xl animate-float inline-block">🏅</span>
            <p class="text-muted text-sm mt-2">{{ __('student.no_badges_yet') }}</p>
            <p class="text-faint text-xs">{{ __('student.keep_learning_badges') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection