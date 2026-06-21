@extends('layouts.app')
@section('title', __('parent.my_children'))
@section('page-title', __('parent.my_children'))
@section('page-subtitle', __('parent.my_children_subtitle'))

@section('content')
<div class="space-y-5">

    @if($children->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 stagger">
        @foreach($children as $child)
        <div class="card card-hover">

            {{-- Header --}}
            <a href="{{ route('parent.children.show', $child) }}" class="flex items-center gap-4 mb-4 group">
                <div class="avatar-ring flex-shrink-0">
                    <img src="{{ $child->avatar_url }}" class="w-16 h-16 object-cover" alt="">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-main text-lg group-hover:text-brand-500 transition truncate">
                        {{ $child->name }}
                    </p>
                    <p class="text-xs text-muted mt-0.5">
                        {{ $child->studentProfile?->academicLevel?->name ?? '—' }}
                        @if($child->classrooms->first()) · {{ $child->classrooms->first()->name }} @endif
                    </p>
                    <span class="badge-{{ $child->studentProfile?->status_color ?? 'gray' }} mt-1.5">
                        {{ $child->studentProfile?->status_label ?? '—' }}
                    </span>
                </div>
            </a>

            {{-- Gamification --}}
            @if($child->gamification)
            <div class="bg-surface2 rounded-2xl p-4 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-main flex items-center gap-1.5">
                        🏆 {{ $child->gamification->level_title }}
                    </span>
                    <span class="badge-brand">{{ __('student.level') }} {{ $child->gamification->level }}</span>
                </div>
                <div class="progress-track mb-1.5">
                    <div class="progress-fill" style="width: {{ $child->gamification->level_progress }}%"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-muted">
                    <span>{{ $child->gamification->level_progress }}% {{ __('parent.to_next_level') }}</span>
                    <span class="font-bold text-brand-500">{{ number_format($child->gamification->total_points) }} {{ __('parent.points') }}</span>
                </div>
            </div>
            @endif

            {{-- Quick Stats --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                @php
                    $lessonsCount = $child->lessonProgress()->where('is_completed', true)->count();
                    $avgScore     = round($child->quizAttempts()->where('status','graded')->avg('percentage') ?? 0);
                    $badgesCount  = $child->badges()->count();
                @endphp
                <div class="text-center p-2.5 rounded-xl bg-brand-50">
                    <p class="font-black text-brand-600">{{ $lessonsCount }}</p>
                    <p class="text-[10px] text-brand-600">{{ __('parent.lessons_label') }}</p>
                </div>
                <div class="text-center p-2.5 rounded-xl bg-success-50">
                    <p class="font-black text-success-600">{{ $avgScore }}%</p>
                    <p class="text-[10px] text-success-600">{{ __('parent.avg_label') }}</p>
                </div>
                <div class="text-center p-2.5 rounded-xl bg-accent-50">
                    <p class="font-black text-accent-600">{{ $badgesCount }}</p>
                    <p class="text-[10px] text-accent-600">{{ __('parent.badges_label') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="grid grid-cols-3 gap-2">
                <a href="{{ route('parent.children.show', $child) }}"
                   class="text-center text-xs font-bold py-2.5 rounded-xl bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                    👤 {{ __('parent.profile') }}
                </a>
                <a href="{{ route('parent.children.schedule', $child) }}"
                   class="text-center text-xs font-bold py-2.5 rounded-xl bg-info-50 text-info-600 hover:bg-info-50/70 transition">
                    📅 {{ __('parent.view_schedule') }}
                </a>
                <a href="{{ route('parent.children.reports', $child) }}"
                   class="text-center text-xs font-bold py-2.5 rounded-xl bg-success-50 text-success-600 hover:bg-success-50/70 transition">
                    📊 {{ __('parent.view_reports') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">👶</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('parent.no_children') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('parent.no_children_subtitle') }}</p>
    </div>
    @endif
</div>
@endsection