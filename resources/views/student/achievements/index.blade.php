@extends('layouts.app')
@section('title', __('app.achievements'))
@section('page-title', __('app.achievements'))
@section('page-subtitle', __('student.achievements_subtitle'))

@section('content')
<div class="space-y-6">

    {{-- ══════════ Level Banner ══════════ --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white animate-fade-up"
         style="background: linear-gradient(135deg, #7C3AED, #4F46E5 60%, #1E1B4B)">
        <div class="absolute w-64 h-64 rounded-full bg-white/10 blur-3xl -top-20 end-0 animate-pulse-glow"></div>
        <div class="absolute w-48 h-48 rounded-full bg-accent-400/20 blur-3xl bottom-0 start-0 animate-pulse-glow" style="animation-delay:.8s"></div>
        <div class="absolute inset-0 opacity-[0.06]"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 22px 22px;"></div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">

            {{-- Level Badge --}}
            <div class="relative flex-shrink-0">
                @php
                    $circumference = 2 * 3.14159265 * 52;
                    $offset = $circumference * (1 - ($gamify->level_progress / 100));
                @endphp
                <svg viewBox="0 0 120 120" class="w-28 h-28 -rotate-90">
                    <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="8"/>
                    <circle cx="60" cy="60" r="52" fill="none" stroke="#FBBF24" stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $offset }}"
                            style="transition: stroke-dashoffset 1.2s ease"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-black">{{ $gamify->level }}</span>
                    <span class="text-[10px] text-white/60 -mt-0.5">{{ __('student.level') }}</span>
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/60 text-sm">{{ __('student.your_level') }}</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5">{{ $gamify->level_title }}</h2>
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs text-white/60 mb-1.5">
                        <span>{{ __('student.progress_to_next_level') }}</span>
                        <span class="font-bold text-accent-400">{{ $gamify->level_progress }}%</span>
                    </div>
                    <div class="w-full bg-white/15 rounded-full h-2.5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-accent-400 to-accent-500"
                             style="width: {{ $gamify->level_progress }}%; transition: width 1s ease"></div>
                    </div>
                </div>
            </div>

            {{-- Points --}}
            <div class="text-center flex-shrink-0">
                <p class="text-white/60 text-sm">{{ __('student.total_points') }}</p>
                <p class="text-4xl font-black text-accent-400 mt-0.5">{{ number_format($gamify->total_points) }}</p>
                <p class="text-white/60 text-xs">{{ __('student.pts') }}</p>
            </div>
        </div>
    </div>

    {{-- ══════════ Stats Row ══════════ --}}
    <div class="grid grid-cols-3 gap-4 stagger">
        @foreach([
            ['label'=>__('app.this_week'),  'value'=>number_format($gamify->weekly_points),  'icon'=>'📅'],
            ['label'=>__('app.this_month'), 'value'=>number_format($gamify->monthly_points), 'icon'=>'📆'],
            ['label'=>__('student.badges_earned'), 'value'=>$earnedBadges->count(), 'icon'=>'🏅'],
        ] as $s)
        <div class="card text-center animate-scale-in">
            <p class="text-3xl mb-2">{{ $s['icon'] }}</p>
            <p class="text-2xl font-black text-main">{{ $s['value'] }}</p>
            <p class="text-muted text-xs mt-0.5">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ══════════ Earned Badges ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.06s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center">🏅</span>
                {{ __('student.my_badges') }} ({{ $earnedBadges->count() }})
            </h3>

            @forelse($earnedBadges as $badge)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 transition-transform hover:scale-110 hover:rotate-3"
                     style="background: linear-gradient(135deg, {{ $badge->color }}33, {{ $badge->color }}15);
                            border: 2px solid {{ $badge->color }}40">
                    {{ $badge->icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-main truncate">{{ $badge->name }}</p>
                    <p class="text-xs text-muted mt-0.5">{{ $badge->description }}</p>
                    <p class="text-xs text-accent-600 mt-0.5">
                        🏆 +{{ $badge->points_reward }} {{ __('student.pts') }}
                        · {{ $badge->pivot->earned_at->diffForHumans() }}
                    </p>
                </div>
                <form method="POST" action="{{ route('student.badges.feature', $badge) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="text-2xl transition-transform hover:scale-125 {{ $badge->pivot->is_featured ? 'text-accent-400' : 'text-faint' }}">
                        ⭐
                    </button>
                </form>
            </div>
            @empty
            <div class="text-center py-8 animate-fade">
                <span class="text-5xl animate-float inline-block">🏅</span>
                <p class="text-muted text-sm mt-3 font-bold">{{ __('student.no_badges_yet') }}</p>
                <p class="text-faint text-xs">{{ __('student.keep_learning_badges') }}</p>
            </div>
            @endforelse
        </div>

        {{-- ══════════ Locked Badges ══════════ --}}
        <div class="card animate-fade-up" style="animation-delay:.08s">
            <h3 class="font-bold text-main flex items-center gap-2 mb-4">
                <span class="w-9 h-9 rounded-xl bg-surface2 text-muted flex items-center justify-center">🔒</span>
                {{ __('student.upcoming_badges') }}
            </h3>
            @forelse($lockedBadges->take(6) as $badge)
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd bg-surface2/50 mb-2 opacity-60 hover:opacity-80 transition">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 grayscale"
                     style="background: var(--bg-hover); border: 2px solid var(--border-app)">
                    {{ $badge->icon }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-muted truncate">{{ $badge->name }}</p>
                    <p class="text-xs text-faint mt-0.5">{{ $badge->description }}</p>
                    <p class="text-xs text-faint mt-0.5">🏆 +{{ $badge->points_reward }} {{ __('student.pts') }}</p>
                </div>
                <span class="text-faint text-2xl">🔒</span>
            </div>
            @empty
            <div class="text-center py-6 animate-fade">
                <span class="text-3xl">🎉</span>
                <p class="text-muted text-sm mt-2">{{ __('student.all_badges_earned') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ══════════ Leaderboard ══════════ --}}
    @if(count($leaderboard))
    <div class="card animate-fade-up" style="animation-delay:.10s">
        <h3 class="font-bold text-main flex items-center gap-2 mb-4">
            <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center">🥇</span>
            {{ __('app.leaderboard') }} — {{ __('student.my_classroom') }}
        </h3>

        <div class="space-y-2 stagger">
            @foreach($leaderboard as $i => $u)
            <div class="flex items-center gap-3 p-3 rounded-2xl border transition
                        {{ $u->id === auth()->id()
                            ? 'border-brand-400/40 bg-brand-50/60 shadow-glow'
                            : 'border-bd hover:bg-hover' }}">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0
                            {{ $i===0 ? 'bg-warning-50 text-warning-600'
                              : ($i===1 ? 'bg-surface2 text-muted'
                              : ($i===2 ? 'bg-accent-50 text-accent-600' : 'bg-surface2 text-faint')) }}">
                    {{ $i===0 ? '🥇' : ($i===1 ? '🥈' : ($i===2 ? '🥉' : $i+1)) }}
                </div>
                <img src="{{ $u->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-main text-sm truncate">
                        {{ $u->name }}
                        @if($u->id === auth()->id())
                        <span class="badge-brand ms-1 text-xs">{{ __('student.you') }}</span>
                        @endif
                    </p>
                    <p class="text-xs text-muted">{{ $u->gamification?->level_title }}</p>
                </div>
                <div class="text-end flex-shrink-0">
                    <p class="font-black text-brand-500">{{ number_format($u->gamification?->total_points ?? 0) }}</p>
                    <p class="text-xs text-faint">{{ __('student.pts') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════ Points History ══════════ --}}
    <div class="card animate-fade-up" style="animation-delay:.12s">
        <h3 class="font-bold text-main flex items-center gap-2 mb-4">
            <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center">📜</span>
            {{ __('student.points_history') }}
        </h3>
        @forelse($transactions as $t)
        <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-hover transition mb-2 border border-bd">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg flex-shrink-0
                        {{ $t->points > 0 ? 'bg-success-50 text-success-600' : 'bg-danger-50 text-danger-600' }}">
                {{ $t->points > 0 ? '⬆️' : '⬇️' }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-main truncate">{{ $t->description ?: $t->action_label }}</p>
                <p class="text-xs text-faint">{{ $t->created_at->diffForHumans() }}</p>
            </div>
            <span class="font-black text-sm flex-shrink-0 {{ $t->points > 0 ? 'text-success-600' : 'text-danger-600' }}">
                {{ $t->points > 0 ? '+' : '' }}{{ $t->points }}
            </span>
        </div>
        @empty
        <div class="text-center py-6 animate-fade">
            <span class="text-3xl animate-float inline-block">📜</span>
            <p class="text-muted text-sm mt-2">{{ __('student.no_transactions') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection