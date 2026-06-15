@extends('layouts.app')
@section('title', __('counselor.welcome'))
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

        <div class="absolute top-8 end-1/4 text-3xl animate-float opacity-30 hidden sm:block">💬</div>
        <div class="absolute bottom-10 end-12 text-2xl animate-float opacity-30 hidden sm:block" style="animation-delay:.8s">🧠</div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">

            <div class="avatar-ring flex-shrink-0 animate-scale-in !p-[3px]">
                <img src="{{ auth()->user()->avatar_url }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover" alt="">
            </div>

            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/65 text-sm">{{ __('app.'.$greetingKey) }} 👋</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">{{ auth()->user()->name }}</h2>
                <p class="text-white/60 text-sm mt-1">
                    {{ __('counselor.subtitle') }} · {{ __('app.'.$dayName) }}, {{ now()->format('d/m/Y') }}
                </p>
            </div>

            <div class="hidden lg:flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-5xl animate-float">🧑‍💼</span>
            </div>
        </div>
    </div>

    {{-- ══════════ Quick Stats ══════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 stagger">
        @foreach([
            ['label' => __('counselor.total_students'), 'value' => $stats['total_students'], 'icon' => '👨‍🎓', 'ring' => 'brand'],
            ['label' => __('counselor.at_risk'),         'value' => $stats['at_risk'],         'icon' => '🚨', 'ring' => 'danger', 'pulse' => $stats['at_risk'] > 0],
            ['label' => __('counselor.needs_support'),   'value' => $stats['needs_support'],   'icon' => '⚠️', 'ring' => 'warning'],
            ['label' => __('counselor.excellent'),       'value' => $stats['excellent'],       'icon' => '⭐', 'ring' => 'success'],
        ] as $s)
        <div class="card card-hover relative">
            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3
                        bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600 {{ ($s['pulse'] ?? false) ? 'animate-pulse-glow' : '' }}">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ══════════ Urgent Follow-up List ══════════ --}}
        <div class="card lg:col-span-2 animate-fade-up" style="animation-delay:.05s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-danger-50 text-danger-600 flex items-center justify-center text-base">🚨</span>
                    {{ __('counselor.urgent_followup') }}
                </h3>
                <a href="{{ route('counselor.students.index', ['status' => 'at_risk']) }}"
                   class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    {{ __('app.view_all') }}
                </a>
            </div>

            @forelse($atRiskStudents as $student)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2 animate-slide">
                <img src="{{ $student->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate">{{ $student->name }}</p>
                    <p class="text-xs text-muted">
                        {{ $student->studentProfile?->academicLevel?->name ?? '—' }}
                        @if($student->classrooms->first()) · {{ $student->classrooms->first()->name }} @endif
                    </p>
                </div>
                <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }} flex-shrink-0">
                    {{ $student->studentProfile?->status_label ?? '—' }}
                </span>
                <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('counselor.students.show', $student) }}"
                       class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                        {{ __('counselor.view_profile') }}
                    </a>
                    <span class="text-faint">·</span>
                    <a href="{{ route('counselor.reports.create') }}?student_id={{ $student->id }}"
                       class="text-xs font-bold text-success-600 hover:text-success-500 transition">
                        {{ __('counselor.write_report') }}
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-10 animate-fade">
                <span class="text-5xl animate-float inline-block">🎉</span>
                <p class="text-muted text-sm mt-3 font-bold">{{ __('counselor.no_at_risk') }}</p>
                <p class="text-faint text-xs">{{ __('counselor.all_good') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Quick Actions ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">⚡</span>
                    {{ __('counselor.quick_actions') }}
                </h3>
            </div>

            <div class="space-y-3 stagger">
                <a href="{{ route('counselor.students.index') }}"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-brand-50 hover:bg-brand-100 hover:-translate-y-1 transition-all text-brand-600">
                    <span class="text-2xl">👨‍🎓</span>
                    <span class="text-sm font-bold flex-1">{{ __('counselor.view_all_students') }}</span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('counselor.reports.create') }}"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-success-50 hover:bg-success-50/70 hover:-translate-y-1 transition-all text-success-600">
                    <span class="text-2xl">📝</span>
                    <span class="text-sm font-bold flex-1">{{ __('counselor.new_report') }}</span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('counselor.reports.index') }}"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-info-50 hover:bg-info-50/70 hover:-translate-y-1 transition-all text-info-600">
                    <span class="text-2xl">📚</span>
                    <span class="text-sm font-bold flex-1">{{ __('counselor.reports_history') }}</span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection