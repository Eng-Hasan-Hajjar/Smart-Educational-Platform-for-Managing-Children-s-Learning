@extends('layouts.app')
@section('title', __('app.reports'))
@section('page-title', __('app.reports'))
@section('page-subtitle', __('counselor.reports_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $reports->total() }} {{ __('app.reports') }}</p>
        <a href="{{ route('counselor.reports.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('counselor.new_report') }}
        </a>
    </div>

    {{-- ══════════ Search ══════════ --}}
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input ps-10" placeholder="{{ __('counselor.search_reports') }}">
            </div>
            <button type="submit" class="btn-outline">🔍 {{ __('app.search') }}</button>
        </form>
    </div>

    {{-- ══════════ Reports List ══════════ --}}
    @forelse($reports as $report)
    <div class="card card-hover animate-fade-up" style="animation-delay:{{ .06 + $loop->index * .03 }}s">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ $report->student->avatar_url }}" class="w-11 h-11 rounded-full object-cover flex-shrink-0" alt="">
                <div class="min-w-0">
                    <p class="font-bold text-main truncate">{{ $report->student->name }}</p>
                    <p class="text-xs text-muted mt-0.5">
                        {{ $report->type_label }} · {{ $report->semester->name }} · {{ $report->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <span class="badge-{{ $report->is_sent_to_parent ? 'green' : 'gray' }}">
                    {{ $report->is_sent_to_parent ? __('counselor.sent_to_parent') : __('counselor.not_sent_yet') }}
                </span>
                <a href="{{ route('counselor.reports.show', $report) }}" class="btn-outline !py-2 !px-3 text-xs">
                    {{ __('app.show') }}
                </a>
            </div>
        </div>
        @if($report->counselor_notes)
        <p class="text-sm text-muted mt-3 line-clamp-2">{{ $report->counselor_notes }}</p>
        @endif
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📝</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('counselor.no_reports_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('counselor.no_reports_hint') }}</p>
        <a href="{{ route('counselor.reports.create') }}" class="btn-primary mt-5 inline-flex">
            {{ __('counselor.write_first_report') }}
        </a>
    </div>
    @endforelse

    @if($reports->hasPages())
    <div class="flex justify-center animate-fade-up">{{ $reports->withQueryString()->links() }}</div>
    @endif
</div>
@endsection