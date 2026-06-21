@extends('layouts.app')
@section('title', __('parent.view_reports') . ' — ' . $student->name)
@section('page-title', __('app.reports'))
@section('page-subtitle', $student->name . ' · ' . ($student->studentProfile?->academicLevel?->name ?? ''))

@section('content')
<div class="space-y-6">

    <a href="{{ route('parent.children.show', $student) }}" class="btn-outline !py-2 !px-3 text-xs inline-flex animate-fade-up">
        <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('app.back') }}
    </a>

    {{-- ══════════ Subject Analytics ══════════ --}}
    @if($analytics->count())
    <div class="card animate-fade-up" style="animation-delay:.04s">
        <h3 class="font-bold text-main flex items-center gap-2 mb-4">
            <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📊</span>
            {{ __('parent.subject_performance') }}
        </h3>
        <div class="space-y-4">
            @foreach($analytics as $a)
            @php
                $color = $a->average_score >= 80 ? 'success' : ($a->average_score >= 60 ? 'warning' : 'danger');
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <p class="text-sm font-bold text-main">{{ $a->subject->name ?? '—' }}</p>
                    <span class="text-xs font-black text-{{ $color }}-600">{{ round($a->average_score) }}%</span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill !bg-none"
                         style="width: {{ $a->average_score }}%; background: var(--{{ $color }}-500)"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════ Official Reports ══════════ --}}
    <div class="space-y-4">
        <h3 class="font-bold text-main flex items-center gap-2 animate-fade-up" style="animation-delay:.06s">
            <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">📝</span>
            {{ __('parent.official_reports') }}
        </h3>

        @forelse($reports as $report)
        <div class="card animate-fade-up" style="animation-delay:{{ .08 + $loop->index * .03 }}s"
             x-data="{ open: false }">
            <button @click="open = !open" type="button" class="w-full flex items-center justify-between text-start">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl flex-shrink-0">
                        📋
                    </div>
                    <div>
                        <p class="font-bold text-main">{{ $report->type_label }}</p>
                        <p class="text-xs text-muted mt-0.5">
                            {{ $report->semester->name }} · {{ $report->semester->academicYear->name }}
                            · {{ $report->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-faint transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" x-cloak x-transition class="mt-4 pt-4 border-t border-bd space-y-3">
                @if($report->subjects_data)
                @foreach($report->subjects_data as $subj)
                <div class="flex items-center gap-3">
                    <p class="text-sm text-muted w-28 truncate flex-shrink-0">{{ $subj['subject'] ?? '—' }}</p>
                    <div class="flex-1 progress-track">
                        <div class="progress-fill" style="width: {{ $subj['average_score'] ?? 0 }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-brand-500 w-10 flex-shrink-0">{{ round($subj['average_score'] ?? 0) }}%</span>
                </div>
                @endforeach
                @endif

                @if($report->counselor_notes)
                <div class="p-3.5 rounded-2xl bg-info-50 border border-info-500/20">
                    <p class="text-xs font-bold text-info-600 mb-1">🧑‍💼 {{ __('parent.counselor_notes') }}:</p>
                    <p class="text-sm text-main leading-relaxed">{{ $report->counselor_notes }}</p>
                </div>
                @endif

                @if($report->recommendations)
                <div class="p-3.5 rounded-2xl bg-success-50 border border-success-500/20">
                    <p class="text-xs font-bold text-success-600 mb-1">💡 {{ __('parent.recommendations') }}:</p>
                    <p class="text-sm text-main leading-relaxed">{{ $report->recommendations }}</p>
                </div>
                @endif

                <p class="text-xs text-faint">
                    {{ __('parent.report_by') }}: {{ $report->generatedBy->name }}
                </p>
            </div>
        </div>
        @empty
        <div class="card text-center py-12 animate-fade">
            <span class="text-5xl animate-float inline-block">📝</span>
            <p class="text-muted text-sm mt-3 font-bold">{{ __('parent.no_official_reports') }}</p>
            <p class="text-faint text-xs mt-1">{{ __('parent.no_official_reports_hint') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection