@extends('layouts.app')
@section('title', __('app.quizzes'))
@section('page-title', __('app.quizzes'))
@section('page-subtitle', __('teacher.quizzes_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $quizzes->total() }} {{ __('teacher.quizzes_count') }}</p>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('teacher.new_quiz') }}
        </a>
    </div>

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="input sm:w-48">
                <option value="">{{ __('app.all') }}</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>{{ __('status.draft') }}</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>{{ __('status.published') }}</option>
                <option value="archived"  {{ request('status') === 'archived'  ? 'selected' : '' }}>{{ __('status.archived') }}</option>
            </select>
            <select name="type" class="input sm:w-48">
                <option value="">{{ __('teacher.all_types') }}</option>
                @foreach(['lesson_quiz'=>__('teacher.type_lesson_quiz'), 'unit_test'=>__('teacher.type_unit_test'), 'midterm'=>__('teacher.type_midterm'), 'final'=>__('teacher.type_final'), 'practice'=>__('teacher.type_practice')] as $v=>$l)
                <option value="{{ $v }}" {{ request('type') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                {{ __('app.filter') }}
            </button>
            @if(request()->hasAny(['status','type']))
            <a href="{{ route('teacher.quizzes.index') }}" class="btn-outline text-danger-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════ Quiz Cards ══════════ --}}
    @forelse($quizzes as $quiz)
    <div class="card card-hover animate-fade-up" style="animation-delay:{{ .06 + $loop->index * .04 }}s">
        <div class="flex items-start gap-4">

            {{-- Icon --}}
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        {{ $quiz->status === 'published' ? 'bg-success-50 text-success-600' : 'bg-hover text-faint' }}">
                📝
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="badge-{{ $quiz->status === 'published' ? 'green' : ($quiz->status === 'archived' ? 'gray' : 'yellow') }}">
                        {{ __('status.'.$quiz->status) }}
                    </span>
                    <span class="badge-brand">{{ __('teacher.type_'.$quiz->type) }}</span>
                    @if($quiz->subject)
                    <span class="text-xs text-muted">{{ $quiz->subject->name }}</span>
                    @endif
                </div>

                <a href="{{ route('teacher.quizzes.edit', $quiz) }}"
                   class="font-bold text-main hover:text-brand-500 transition text-base">
                    {{ $quiz->title }}
                </a>

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-faint">
                    <span class="flex items-center gap-1">
                        ❓ {{ $quiz->questions_count }} {{ __('teacher.questions') }}
                    </span>
                    <span class="flex items-center gap-1">
                        🎯 {{ $quiz->total_marks }} {{ __('teacher.marks') }}
                    </span>
                    <span class="flex items-center gap-1">
                        ⏱ {{ $quiz->duration_minutes ?? '—' }} {{ __('student.min') }}
                    </span>
                    <span class="flex items-center gap-1">
                        🔄 {{ $quiz->max_attempts }} {{ __('teacher.attempts') }}
                    </span>
                    <span class="flex items-center gap-1">
                        👥 {{ $quiz->attempts_count }} {{ __('teacher.students_attempted') }}
                    </span>
                </div>

                {{-- Availability --}}
                @if($quiz->available_from || $quiz->available_until)
                <div class="flex items-center gap-2 mt-2 text-xs text-muted">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $quiz->available_from?->format('d/m/Y H:i') ?? '—' }}
                    →
                    {{ $quiz->available_until?->format('d/m/Y H:i') ?? '—' }}
                </div>
                @endif

                {{-- Progress bar: attempts --}}
                @if($quiz->attempts_count > 0)
                <div class="mt-3 flex items-center gap-2">
                    <div class="flex-1 progress-track">
                        @php $passed = $quiz->attempts()->where('is_passed', true)->count(); @endphp
                        <div class="progress-fill !bg-none"
                             style="width: {{ $quiz->attempts_count > 0 ? round($passed/$quiz->attempts_count*100) : 0 }}%;
                                    background: var(--success-500)"></div>
                    </div>
                    <span class="text-xs text-success-600 font-bold flex-shrink-0">
                        {{ $passed }}/{{ $quiz->attempts_count }} {{ __('teacher.passed') }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn-outline !py-2 !px-3 text-xs">
                    ✏️ {{ __('app.edit') }}
                </a>
                <form method="POST" action="{{ route('teacher.quizzes.publish', $quiz) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-full text-xs font-bold px-3 py-2 rounded-xl transition
                                   {{ $quiz->status === 'published'
                                        ? 'bg-warning-50 text-warning-600 hover:bg-warning-50/70'
                                        : 'bg-success-50 text-success-600 hover:bg-success-50/70' }}">
                        {{ $quiz->status === 'published' ? '🙈 '.__('teacher.unpublish') : '🚀 '.__('teacher.publish') }}
                    </button>
                </form>
                <form method="POST" action="{{ route('teacher.quizzes.destroy', $quiz) }}"
                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 transition">
                        🗑️ {{ __('app.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📝</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.no_quizzes_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_quizzes_hint') }}</p>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn-primary mt-5 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('teacher.create_first_quiz') }}
        </a>
    </div>
    @endforelse

    @if($quizzes->hasPages())
    <div class="flex justify-center animate-fade-up">
        {{ $quizzes->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection