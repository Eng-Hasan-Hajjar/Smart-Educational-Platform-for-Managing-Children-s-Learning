@extends('layouts.app')
@section('title', __('app.assignments'))
@section('page-title', __('app.assignments'))
@section('page-subtitle', __('teacher.assignments_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $assignments->total() }} {{ __('teacher.assignments_count') }}</p>
        <a href="{{ route('teacher.assignments.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('teacher.new_assignment') }}
        </a>
    </div>

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="input sm:w-48">
                <option value="">{{ __('app.all') }}</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>{{ __('status.draft') }}</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>{{ __('status.published') }}</option>
                <option value="closed"    {{ request('status') === 'closed'    ? 'selected' : '' }}>{{ __('teacher.assignment_closed') }}</option>
            </select>
            <select name="subject_id" class="input sm:w-48">
                <option value="">{{ __('app.subjects') }}</option>
                @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                {{ __('app.filter') }}
            </button>
            @if(request()->hasAny(['status','subject_id']))
            <a href="{{ route('teacher.assignments.index') }}" class="btn-outline text-danger-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════ Assignments List ══════════ --}}
    @forelse($assignments as $assignment)
    @php
        $overdue        = $assignment->isOverdue() && $assignment->status === 'published';
        $totalStudents  = $assignment->classroom->students()->count();
        $submittedCount = $assignment->submissions_count;
        $gradedCount    = $assignment->graded_count;
        $submitPct      = $totalStudents > 0 ? round($submittedCount / $totalStudents * 100) : 0;
    @endphp

    <div class="card card-hover animate-fade-up" style="animation-delay:{{ .06 + $loop->index * .04 }}s">
        <div class="flex items-start gap-4">

            {{-- Status Icon --}}
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        {{ $assignment->status === 'published' && !$overdue
                            ? 'bg-success-50 text-success-600'
                            : ($overdue ? 'bg-danger-50 text-danger-600' : 'bg-hover text-faint') }}">
                {{ $overdue ? '⌛' : ($assignment->status === 'published' ? '📋' : '📝') }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="badge-{{ $assignment->status === 'published' ? ($overdue ? 'red' : 'green') : ($assignment->status === 'closed' ? 'gray' : 'yellow') }}">
                        {{ $overdue ? __('teacher.overdue') : __('status.'.$assignment->status) }}
                    </span>
                    @if($assignment->subject)
                    <span class="text-xs text-muted">{{ $assignment->subject->name }}</span>
                    @endif
                    <span class="text-faint text-xs">·</span>
                    <span class="text-xs text-muted">{{ $assignment->classroom->name }}</span>
                </div>

                <a href="{{ route('teacher.assignments.edit', $assignment) }}"
                   class="font-bold text-main hover:text-brand-500 transition text-base">
                    {{ $assignment->title }}
                </a>

                @if($assignment->description)
                <p class="text-muted text-xs mt-1 line-clamp-2">{{ $assignment->description }}</p>
                @endif

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-faint">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ __('teacher.due') }}: <span class="{{ $overdue ? 'text-danger-600 font-bold' : '' }}">{{ $assignment->due_date->format('d/m/Y - H:i') }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        🎯 {{ $assignment->total_marks }} {{ __('teacher.marks') }}
                    </span>
                    <span class="flex items-center gap-1">
                        📤 {{ $submittedCount }}/{{ $totalStudents }} {{ __('teacher.submitted') }}
                    </span>
                    @if($gradedCount > 0)
                    <span class="flex items-center gap-1 text-success-600">
                        ✅ {{ $gradedCount }} {{ __('teacher.graded') }}
                    </span>
                    @endif
                </div>

                {{-- Submission Progress Bar --}}
                <div class="mt-3 flex items-center gap-2">
                    <div class="flex-1 progress-track">
                        <div class="progress-fill !bg-none"
                             style="width: {{ $submitPct }}%;
                                    background: {{ $submitPct >= 80 ? 'var(--success-500)' : ($submitPct >= 40 ? 'var(--warning-500)' : 'var(--brand-500)') }}">
                        </div>
                    </div>
                    <span class="text-xs font-bold text-muted flex-shrink-0">{{ $submitPct }}%</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="{{ route('teacher.assignments.submissions', $assignment) }}"
                   class="btn-primary !py-2 !px-3 text-xs relative">
                    📥 {{ __('teacher.submissions') }}
                    @if($submittedCount - $gradedCount > 0)
                    <span class="absolute -top-1 -end-1 w-4 h-4 bg-danger-500 text-white text-[10px] font-black rounded-full flex items-center justify-center">
                        {{ $submittedCount - $gradedCount }}
                    </span>
                    @endif
                </a>
                <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn-outline !py-2 !px-3 text-xs">
                    ✏️ {{ __('app.edit') }}
                </a>
                <form method="POST" action="{{ route('teacher.assignments.destroy', $assignment) }}"
                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 transition">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📋</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.no_assignments_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_assignments_hint') }}</p>
        <a href="{{ route('teacher.assignments.create') }}" class="btn-primary mt-5 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('teacher.create_first_assignment') }}
        </a>
    </div>
    @endforelse

    @if($assignments->hasPages())
    <div class="flex justify-center animate-fade-up">
        {{ $assignments->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection