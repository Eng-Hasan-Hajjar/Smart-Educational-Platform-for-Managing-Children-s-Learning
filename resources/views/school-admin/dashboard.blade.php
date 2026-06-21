@extends('layouts.app')
@section('title', __('schooladmin.welcome'))
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

        <div class="absolute w-64 h-64 rounded-full bg-accent-400/20 blur-3xl -top-16 end-[-3rem] animate-pulse-glow"></div>
        <div class="absolute w-72 h-72 rounded-full bg-brand-400/15 blur-3xl -bottom-20 start-[-4rem] animate-pulse-glow" style="animation-delay:1s"></div>
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>

        <div class="absolute top-8 end-1/4 text-3xl animate-float opacity-30 hidden sm:block">🏫</div>
        <div class="absolute bottom-10 end-12 text-2xl animate-float opacity-30 hidden sm:block" style="animation-delay:.8s">📈</div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">

            <div class="avatar-ring flex-shrink-0 animate-scale-in !p-[3px]">
                <img src="{{ auth()->user()->avatar_url }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover" alt="">
            </div>

            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/65 text-sm">{{ __('app.'.$greetingKey) }} 👋</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">{{ auth()->user()->name }}</h2>
                <p class="text-white/60 text-sm mt-1">
                    {{ __('schooladmin.subtitle') }}
                    @if(auth()->user()->school) · {{ auth()->user()->school->name }} @endif
                </p>
            </div>

            <div class="hidden lg:flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-5xl animate-float">🏫</span>
            </div>
        </div>
    </div>

    {{-- ══════════ At-Risk Alert ══════════ --}}
    @if($atRiskCount > 0)
    <div class="card !p-4 border-danger-500/30 bg-danger-50 animate-fade-up flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-danger-500/15 text-danger-600 flex items-center justify-center text-2xl flex-shrink-0 animate-pulse-glow">
            ⚠️
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-danger-600">{{ __('schooladmin.at_risk_alert_title') }}</p>
            <p class="text-sm text-main mt-0.5">{{ __('schooladmin.at_risk_alert_body', ['count' => $atRiskCount]) }}</p>
        </div>
        <a href="{{ route('school-admin.students.index', ['status' => 'at_risk']) }}" class="btn-danger flex-shrink-0">
            {{ __('schooladmin.review_now') }}
        </a>
    </div>
    @endif

    {{-- ══════════ Quick Stats ══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 stagger">
        @foreach([
            ['label' => __('schooladmin.total_students'),    'value' => $stats['students'],   'icon' => '👨‍🎓', 'ring' => 'brand'],
            ['label' => __('schooladmin.total_teachers'),    'value' => $stats['teachers'],   'icon' => '👨‍🏫', 'ring' => 'info'],
            ['label' => __('schooladmin.classrooms'),        'value' => $stats['classrooms'], 'icon' => '🏛️', 'ring' => 'success'],
            ['label' => __('schooladmin.active_subjects'),   'value' => $stats['subjects'],   'icon' => '📚', 'ring' => 'warning'],
            ['label' => __('schooladmin.published_lessons'), 'value' => $stats['lessons'],    'icon' => '🎬', 'ring' => 'brand'],
            ['label' => __('schooladmin.attendance_rate'),   'value' => $stats['attendance_rate'], 'icon' => '✅', 'ring' => 'success', 'suffix' => '%'],
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
               x-text="display + '{{ $s['suffix'] ?? '' }}'"></p>
            <p class="text-muted text-xs mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ══════════ Recent Students ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.05s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👨‍🎓</span>
                    {{ __('schooladmin.recent_students') }}
                </h3>
                <a href="{{ route('school-admin.students.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    {{ __('app.view_all') }}
                </a>
            </div>

            @forelse($recentStudents as $student)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2 animate-slide">
                <img src="{{ $student->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate">{{ $student->name }}</p>
                    <p class="text-xs text-muted">{{ $student->studentProfile?->academicLevel?->name ?? '—' }}</p>
                </div>
                <div class="text-end flex-shrink-0">
                    <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }}">
                        {{ $student->studentProfile?->status_label ?? '—' }}
                    </span>
                    <p class="text-xs text-faint mt-1">{{ __('schooladmin.joined') }} {{ $student->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">👨‍🎓</span>
                <p class="text-muted text-sm mt-2">{{ __('schooladmin.no_students_yet') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Upcoming Events ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">📅</span>
                    {{ __('schooladmin.upcoming_events') }}
                </h3>
            </div>

            @forelse($upcomingEvents as $event)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2 animate-slide">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                     style="background: {{ $event->color ?? 'var(--brand-500)' }}1A; color: {{ $event->color ?? 'var(--brand-500)' }}">
                    📅
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate">{{ $event->title }}</p>
                    <p class="text-xs text-muted">{{ $event->start_datetime->format('d/m/Y - H:i') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">📅</span>
                <p class="text-muted text-sm mt-2">{{ __('schooladmin.no_upcoming_events') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Classrooms Overview ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.15s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-success-50 text-success-600 flex items-center justify-center text-base">🏛️</span>
                    {{ __('schooladmin.classrooms_overview') }}
                </h3>
                <a href="{{ route('school-admin.classrooms.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    {{ __('app.view_all') }}
                </a>
            </div>

            @forelse($classroomsOverview as $classroom)
            @php
                $pct = $classroom->capacity > 0 ? round(($classroom->students_count / $classroom->capacity) * 100) : 0;
                $barColor = $pct >= 90 ? 'danger' : ($pct >= 70 ? 'warning' : 'success');
            @endphp
            <div class="mb-3.5">
                <div class="flex items-center justify-between mb-1.5">
                    <div>
                        <span class="font-bold text-sm text-main">{{ $classroom->name }}</span>
                        <span class="text-xs text-faint">· {{ $classroom->academicLevel->name }}</span>
                    </div>
                    <span class="text-xs font-bold text-{{ $barColor }}-600">
                        {{ $classroom->students_count }}/{{ $classroom->capacity }}
                    </span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill !bg-none"
                         style="width: {{ $pct }}%; background: var(--{{ $barColor }}-500)"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">🏛️</span>
                <p class="text-muted text-sm mt-2">{{ __('schooladmin.no_classrooms_yet') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Quick Actions ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.2s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">⚡</span>
                    {{ __('schooladmin.quick_actions') }}
                </h3>
            </div>

            <div class="grid grid-cols-2 gap-3 stagger">
                <a href="{{ route('school-admin.teachers.create') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-brand-50 hover:bg-brand-100 hover:-translate-y-1 transition-all text-brand-600">
                    <span class="text-3xl">👨‍🏫</span>
                    <span class="text-sm font-bold">{{ __('schooladmin.add_teacher') }}</span>
                </a>
                <a href="{{ route('school-admin.students.create') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-info-50 hover:bg-info-50/70 hover:-translate-y-1 transition-all text-info-600">
                    <span class="text-3xl">👨‍🎓</span>
                    <span class="text-sm font-bold">{{ __('schooladmin.add_student') }}</span>
                </a>
                <a href="{{ route('school-admin.schedules.index') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-warning-50 hover:bg-warning-50/70 hover:-translate-y-1 transition-all text-warning-600">
                    <span class="text-3xl">📅</span>
                    <span class="text-sm font-bold">{{ __('schooladmin.manage_schedule') }}</span>
                </a>
                <a href="{{ route('school-admin.reports.index') }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-success-50 hover:bg-success-50/70 hover:-translate-y-1 transition-all text-success-600">
                    <span class="text-3xl">📊</span>
                    <span class="text-sm font-bold">{{ __('schooladmin.view_reports') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection