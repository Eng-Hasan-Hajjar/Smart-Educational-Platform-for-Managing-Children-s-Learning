@extends('layouts.app')
@section('title', __('app.recommendations'))
@section('page-title', __('app.recommendations'))
@section('page-subtitle', __('student.recommendations_subtitle'))

@section('content')
<div class="space-y-6">

    {{-- Learning Paths --}}
    @if($learningPaths->count())
    <div class="space-y-4 animate-fade-up">
        <h3 class="font-bold text-main flex items-center gap-2">
            <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">🛤️</span>
            {{ __('student.learning_paths') }}
        </h3>
        @foreach($learningPaths as $path)
        <div class="card">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-xl">{{ $path->subject->icon ?? '📚' }}</span>
                    <h4 class="font-bold text-main">{{ $path->subject->name }}</h4>
                </div>
                <span class="badge-brand">{{ $path->current_step }}/{{ $path->total_steps }}</span>
            </div>
            <div class="progress-track mb-2">
                <div class="progress-fill" style="width: {{ $path->progress }}%"></div>
            </div>
            <div class="flex flex-wrap gap-1.5 mt-3">
                @foreach(array_slice($path->steps ?? [], 0, 5) as $i => $step)
                <span class="text-xs px-2.5 py-1 rounded-lg {{ $i < $path->current_step ? 'bg-success-50 text-success-600' : ($i === $path->current_step ? 'bg-brand-50 text-brand-600 font-bold' : 'bg-surface2 text-muted') }}">
                    {{ $step['type'] === 'quiz' ? '📝' : '📚' }} {{ \Illuminate\Support\Str::limit($step['title'], 20) }}
                </span>
                @endforeach
                @if(count($path->steps ?? []) > 5)
                <span class="text-xs text-faint">+{{ count($path->steps) - 5 }} {{ __('app.more') }}</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- AI Recommendations --}}
    <h3 class="font-bold text-main flex items-center gap-2 animate-fade-up">
        <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center">🤖</span>
        {{ __('student.ai_recommendations') }}
    </h3>

    @forelse($recommendations as $rec)
    @php
        $colors = match($rec->type) {
            'warning'  => ['danger', 'border-danger-500/30 bg-danger-50/40'],
            'praise'   => ['success', 'border-success-500/30 bg-success-50/40'],
            'review'   => ['warning', 'border-warning-500/30 bg-warning-50/40'],
            'quiz'     => ['info', 'border-info-500/30 bg-info-50/40'],
            default    => ['brand', 'border-bd'],
        };
    @endphp
    <div class="card !p-4 {{ $colors[1] }} animate-fade-up" style="animation-delay:{{ .03 * $loop->index }}s">
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-xl bg-{{ $colors[0] }}-50 text-{{ $colors[0] }}-600 flex items-center justify-center text-xl flex-shrink-0">
                {{ $rec->type_icon }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-main text-sm">{{ $rec->reason }}</p>
                @if($rec->recommendable)
                <p class="text-xs text-muted mt-1">{{ $rec->recommendable->title ?? '' }}</p>
                @endif
                <div class="flex items-center gap-3 mt-2 text-xs text-faint">
                    <span>{{ __('student.confidence') }}: {{ round($rec->confidence_score * 100) }}%</span>
                    @if($rec->expires_at)
                    <span>{{ __('student.expires') }}: {{ $rec->expires_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
            @if($rec->recommendable && !$rec->is_acted_upon)
            <a href="{{ $rec->recommendable_type === 'App\\Models\\Quiz'
                ? route('student.quizzes.show', $rec->recommendable_id)
                : route('student.lessons.show', $rec->recommendable_id) }}"
               class="btn-primary text-xs flex-shrink-0">
                {{ __('student.go') }} →
            </a>
            @endif
        </div>
    </div>
    @empty
    <div class="card text-center py-12 animate-fade">
        <span class="text-5xl animate-float inline-block">🤖</span>
        <p class="font-bold text-main mt-3">{{ __('student.no_recommendations') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('student.no_recommendations_hint') }}</p>
    </div>
    @endforelse

    @if($recommendations->hasPages())
    <div class="flex justify-center">{{ $recommendations->links() }}</div>
    @endif
</div>
@endsection
