@extends('layouts.app')
@section('title', __('counselor.report_details'))
@section('page-title', __('counselor.report_details'))
@section('page-subtitle', $report->student->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-3 animate-fade-up">
        <a href="{{ route('counselor.reports.index') }}" class="btn-outline !py-2 !px-3 text-xs">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back') }}
        </a>
        @if(!$report->is_sent_to_parent)
        <form method="POST" action="{{ route('counselor.reports.store') }}">
            @csrf
            <input type="hidden" name="report_id" value="{{ $report->id }}">
            <button class="btn-primary !py-2 text-xs">📤 {{ __('counselor.send_to_parent') }}</button>
        </form>
        @endif
    </div>

    <div class="card animate-fade-up" style="animation-delay:.04s">
        <div class="flex items-center gap-4 mb-5 pb-4 border-b border-bd">
            <img src="{{ $report->student->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
            <div>
                <h2 class="text-xl font-extrabold text-main">{{ $report->student->name }}</h2>
                <p class="text-muted text-sm">{{ $report->type_label }} · {{ $report->semester->name }} · {{ $report->semester->academicYear->name }}</p>
                <p class="text-xs text-faint mt-1">{{ __('counselor.written_by') }}: {{ $report->generatedBy->name }} · {{ $report->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        @if($report->subjects_data)
        <div class="mb-5">
            <h4 class="font-bold text-main mb-3">📊 {{ __('counselor.subjects_performance') }}:</h4>
            @foreach($report->subjects_data as $subj)
            <div class="flex items-center gap-3 mb-2">
                <p class="text-sm text-muted w-32 truncate">{{ $subj['subject'] ?? '—' }}</p>
                <div class="flex-1 progress-track">
                    <div class="progress-fill" style="width: {{ $subj['average_score'] ?? 0 }}%"></div>
                </div>
                <span class="text-xs font-bold text-brand-500 w-10">{{ round($subj['average_score'] ?? 0) }}%</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-info-50 border border-info-500/20">
                <p class="text-xs font-bold text-info-600 mb-2">🧑‍💼 {{ __('counselor.counselor_notes_field') }}:</p>
                <p class="text-sm text-main leading-relaxed">{{ $report->counselor_notes }}</p>
            </div>
            @if($report->recommendations)
            <div class="p-4 rounded-2xl bg-success-50 border border-success-500/20">
                <p class="text-xs font-bold text-success-600 mb-2">💡 {{ __('counselor.recommendations_field') }}:</p>
                <p class="text-sm text-main leading-relaxed">{{ $report->recommendations }}</p>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-bd">
            <span class="badge-{{ $report->is_sent_to_parent ? 'green' : 'gray' }}">
                {{ $report->is_sent_to_parent
                    ? __('counselor.sent_to_parent') . ' — ' . $report->sent_at?->format('d/m/Y')
                    : __('counselor.not_sent_yet') }}
            </span>
        </div>
    </div>
</div>
@endsection