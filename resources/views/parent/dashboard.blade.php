@extends('layouts.app')
@section('title', __('parent.welcome'))
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

        <div class="absolute top-8 end-1/4 text-3xl animate-float opacity-30 hidden sm:block">💌</div>
        <div class="absolute bottom-10 end-12 text-2xl animate-float opacity-30 hidden sm:block" style="animation-delay:.8s">🌟</div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">
            <div class="avatar-ring flex-shrink-0 animate-scale-in !p-[3px]">
                <img src="{{ auth()->user()->avatar_url }}" class="w-16 h-16 sm:w-20 sm:h-20 object-cover" alt="">
            </div>

            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/65 text-sm">{{ __('app.'.$greetingKey) }} 👋</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">{{ auth()->user()->name }}</h2>
                <p class="text-white/60 text-sm mt-1">
                    {{ __('parent.subtitle') }} · {{ __('app.'.$dayName) }}, {{ now()->format('d/m/Y') }}
                </p>
            </div>

            <div class="hidden lg:flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-5xl animate-float">👨‍👩‍👧‍👦</span>
            </div>
        </div>
    </div>

    {{-- ══════════ Children Grid ══════════ --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👶</span>
                {{ __('parent.my_children') }}
            </h3>
            @if($children->count())
            <a href="{{ route('parent.children.index') }}" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                {{ __('app.view_all') }}
            </a>
            @endif
        </div>

        @if($children->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 stagger">
            @foreach($children as $child)
            <div class="card card-hover">
                <a href="{{ route('parent.children.show', $child) }}" class="flex items-center gap-4 mb-4 group">
                    <div class="avatar-ring flex-shrink-0">
                        <img src="{{ $child->avatar_url }}" class="w-14 h-14 object-cover" alt="">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-main truncate group-hover:text-brand-500 transition">{{ $child->name }}</p>
                        <p class="text-xs text-muted">{{ $child->studentProfile?->academicLevel?->name ?? '—' }}</p>
                        <span class="badge-{{ $child->studentProfile?->status_color ?? 'gray' }} mt-1">
                            {{ $child->studentProfile?->status_label ?? '—' }}
                        </span>
                    </div>
                </a>

                @if($child->gamification)
                <div class="bg-surface2 rounded-2xl p-3 mb-3">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-muted font-semibold">🏆 {{ $child->gamification->level_title }}</span>
                        <span class="font-black text-brand-500">{{ number_format($child->gamification->total_points) }} {{ __('parent.points') }}</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $child->gamification->level_progress }}%"></div>
                    </div>
                </div>
                @endif

                <div class="flex gap-2">
                    <a href="{{ route('parent.children.schedule', $child) }}"
                       class="flex-1 text-center text-xs font-bold py-2 rounded-xl bg-info-50 text-info-600 hover:bg-info-50/70 transition">
                        📅 {{ __('parent.view_schedule') }}
                    </a>
                    <a href="{{ route('parent.children.reports', $child) }}"
                       class="flex-1 text-center text-xs font-bold py-2 rounded-xl bg-success-50 text-success-600 hover:bg-success-50/70 transition">
                        📊 {{ __('parent.view_reports') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="card text-center py-12 animate-fade">
            <span class="text-5xl animate-float inline-block">👶</span>
            <p class="text-muted mt-3 font-bold">{{ __('parent.no_children') }}</p>
            <p class="text-faint text-sm mt-1">{{ __('parent.no_children_subtitle') }}</p>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ══════════ Upcoming Assignments ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.05s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">📋</span>
                    {{ __('parent.upcoming_assignments') }}
                </h3>
            </div>

            @forelse($upcomingAssignments as $a)
            @php $overdue = $a->isOverdue(); @endphp
            <div class="flex items-center gap-3 p-3 rounded-2xl border mb-2 transition
                        {{ $overdue ? 'border-danger-500/30 bg-danger-50' : 'border-bd hover:bg-hover' }}">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                            {{ $overdue ? 'bg-danger-500/15 text-danger-600' : 'bg-warning-50 text-warning-600' }}">
                    {{ $overdue ? '⚠️' : '📋' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate">{{ $a->title }}</p>
                    <p class="text-xs text-muted">{{ $a->subject->name ?? '—' }} · {{ $a->classroom->name ?? '—' }}</p>
                </div>
                <div class="text-end flex-shrink-0">
                    <span class="badge-{{ $overdue ? 'red' : 'yellow' }}">
                        {{ $overdue ? __('parent.overdue') : __('parent.due') }}
                    </span>
                    <p class="text-xs text-faint mt-1">{{ $a->due_date->format('d/m') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">✅</span>
                <p class="text-muted text-sm mt-2">{{ __('parent.no_assignments') }}</p>
                <p class="text-faint text-xs">{{ __('parent.all_good') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ New Notifications ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">🔔</span>
                    {{ __('parent.new_notifications') }}
                </h3>
            </div>

            @forelse($unreadNotifications as $n)
            <div class="flex items-start gap-3 p-3 rounded-2xl border border-brand-400/20 bg-brand-50 mb-2 animate-slide">
                <span class="relative flex w-2 h-2 mt-1.5 flex-shrink-0">
                    <span class="absolute inline-flex w-full h-full rounded-full bg-brand-500 opacity-75 animate-pulse-glow"></span>
                    <span class="relative inline-flex rounded-full w-2 h-2 bg-brand-500"></span>
                </span>
                <div class="flex-1">
                    <p class="text-sm text-main leading-relaxed">{{ $n->data['message'] ?? __('app.new_notification') }}</p>
                    <p class="text-xs text-faint mt-1">{{ $n->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-4xl animate-float inline-block">🔔</span>
                <p class="text-muted text-sm mt-2">{{ __('app.no_notifications') }}</p>
            </div>
            @endforelse

            @if($unreadNotifications->count())
            <a href="{{ route('notifications.index') }}"
               class="block text-center text-xs font-bold text-brand-500 hover:text-brand-700 transition mt-2 pt-2 border-t border-bd">
                {{ __('parent.view_all_notifications') }} →
            </a>
            @endif
        </div>
    </div>
</div>
@endsection