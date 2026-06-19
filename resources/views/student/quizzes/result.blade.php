@extends('layouts.app')
@section('title', __('student.quiz_result'))
@section('page-title', __('student.quiz_result'))
@section('page-subtitle', $quiz->title)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- ══════════ Result Banner ══════════ --}}
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white animate-scale-in
                {{ $attempt->is_passed
                    ? 'bg-gradient-to-br from-success-600 to-success-500'
                    : 'bg-gradient-to-br from-danger-600 to-danger-500' }}">

        <div class="absolute inset-0 opacity-[0.07]"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 22px 22px;"></div>

        @if($attempt->is_passed)
        <div class="absolute top-6 end-8 text-5xl animate-float opacity-60">🎉</div>
        <div class="absolute bottom-6 start-8 text-4xl animate-float opacity-50" style="animation-delay:.5s">⭐</div>
        @else
        <div class="absolute top-6 end-8 text-5xl animate-float opacity-60">💪</div>
        @endif

        <div class="relative z-10 text-center">
            <p class="text-white/70 text-sm mb-1">
                {{ $attempt->is_passed ? __('student.congratulations') : __('student.try_again') }}
            </p>
            <p class="text-4xl sm:text-5xl font-black mb-2">
                {{ round($attempt->percentage) }}%
            </p>
            <p class="text-white/80 text-lg font-bold">
                {{ $attempt->total_marks_obtained }} / {{ $quiz->total_marks }} {{ __('teacher.marks') }}
            </p>
            @if($attempt->time_taken_seconds)
            <p class="text-white/60 text-sm mt-2">
                ⏱ {{ __('student.time_taken') }}: {{ gmdate('i:s', $attempt->time_taken_seconds) }}
            </p>
            @endif
        </div>
    </div>

    {{-- ══════════ Stats Row ══════════ --}}
    <div class="grid grid-cols-3 gap-4 stagger">
        @php
            $correct  = collect($answers)->where('is_correct', true)->count();
            $total    = $quiz->questions->count();
            $wrong    = $total - $correct;
        @endphp
        @foreach([
            ['label'=>__('student.correct_answers'), 'value'=>$correct, 'icon'=>'✅', 'ring'=>'success'],
            ['label'=>__('student.wrong_answers'),   'value'=>$wrong,   'icon'=>'❌', 'ring'=>'danger'],
            ['label'=>__('student.total_questions'), 'value'=>$total,   'icon'=>'❓', 'ring'=>'brand'],
        ] as $s)
        <div class="card text-center animate-scale-in">
            <p class="text-2xl mb-1.5">{{ $s['icon'] }}</p>
            <p class="text-2xl font-black text-{{ $s['ring'] }}-600">{{ $s['value'] }}</p>
            <p class="text-faint text-xs mt-0.5">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ══════════ Answers Review ══════════ --}}
    @if($quiz->show_correct_answers)
    <div class="space-y-4 animate-fade-up">
        <h3 class="font-bold text-main flex items-center gap-2">
            <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center">📋</span>
            {{ __('student.answers_review') }}
        </h3>

        @foreach($quiz->questions as $i => $question)
        @php $ans = $answers[$question->id] ?? null; @endphp
        <div class="card {{ $ans?->is_correct ? 'border-success-500/30' : 'border-danger-500/30' }} animate-slide"
             style="animation-delay:{{ .03 * $i }}s">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm font-black flex-shrink-0
                            {{ $ans?->is_correct ? 'bg-success-50 text-success-600' : 'bg-danger-50 text-danger-600' }}">
                    {{ $ans?->is_correct ? '✓' : '✗' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-main text-sm leading-relaxed">{{ $question->question_text }}</p>

                    {{-- Student Answer --}}
                    @if($ans?->selectedOption)
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <span class="text-muted text-xs">{{ __('student.your_answer') }}:</span>
                        <span class="{{ $ans->is_correct ? 'text-success-600 font-bold' : 'text-danger-600 font-bold' }}">
                            {{ $ans->selectedOption->option_text }}
                        </span>
                    </div>
                    @elseif($ans?->text_answer)
                    <div class="mt-2 p-2 rounded-xl bg-surface2 border border-bd text-sm">
                        <span class="text-muted text-xs">{{ __('student.your_answer') }}:</span>
                        <span class="ms-1 text-main">{{ $ans->text_answer }}</span>
                    </div>
                    @endif

                    {{-- Correct Answer --}}
                    @if(!$ans?->is_correct && $question->correctOption)
                    <div class="mt-2 flex items-center gap-2 text-sm">
                        <span class="text-muted text-xs">{{ __('student.correct_answer') }}:</span>
                        <span class="text-success-600 font-bold">{{ $question->correctOption->option_text }}</span>
                    </div>
                    @endif

                    {{-- Explanation --}}
                    @if($question->explanation)
                    <div class="mt-3 p-3 rounded-2xl bg-info-50 border border-info-500/20">
                        <p class="text-xs font-bold text-info-600 mb-1">💡 {{ __('student.explanation') }}</p>
                        <p class="text-sm text-main">{{ $question->explanation }}</p>
                    </div>
                    @endif
                </div>
                <span class="badge-{{ $ans?->is_correct ? 'green' : 'gray' }} flex-shrink-0 text-xs">
                    {{ $ans?->marks_obtained ?? 0 }}/{{ $question->marks }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ══════════ Actions ══════════ --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 animate-fade-up">
        <a href="{{ route('student.dashboard') }}" class="btn-outline w-full sm:w-auto justify-center">
            🏠 {{ __('student.back_to_dashboard') }}
        </a>
        @if(!$attempt->is_passed && $quiz->max_attempts > 1)
        <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn-primary w-full sm:w-auto justify-center">
            🔄 {{ __('student.retry_quiz') }}
        </a>
        @endif
        <a href="{{ route('student.lessons.index') }}" class="btn-outline w-full sm:w-auto justify-center">
            📚 {{ __('student.continue_learning') }}
        </a>
    </div>
</div>
@endsection