@extends('layouts.app')
@section('title', $quiz->title)
@section('page-title', $quiz->title)
@section('page-subtitle', $quiz->subject?->name ?? '')

@section('content')
<div class="max-w-3xl mx-auto space-y-6"
     x-data="quizTimer({{ $quiz->duration_minutes ?? 0 }})">

    {{-- ══════════ Quiz Header ══════════ --}}
    <div class="card animate-fade-up"
         style="background: linear-gradient(135deg, var(--brand-50), var(--info-50))">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="badge-brand mb-2">{{ __('teacher.type_'.$quiz->type) }}</span>
                <h2 class="text-xl font-extrabold text-main">{{ $quiz->title }}</h2>
                @if($quiz->instructions)
                <p class="text-muted text-sm mt-1">{{ $quiz->instructions }}</p>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <div class="text-center">
                    <p class="text-2xl font-black text-brand-600">{{ $questions->count() }}</p>
                    <p class="text-xs text-muted">{{ __('teacher.questions') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-black text-success-600">{{ $quiz->total_marks }}</p>
                    <p class="text-xs text-muted">{{ __('teacher.marks') }}</p>
                </div>
                @if($quiz->duration_minutes)
                <div class="text-center bg-warning-50 rounded-2xl px-4 py-2 border border-warning-500/25">
                    <p class="text-xl font-black text-warning-600" x-text="timeDisplay">
                        {{ $quiz->duration_minutes }}:00
                    </p>
                    <p class="text-xs text-warning-600">{{ __('student.remaining') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════ Questions Form ══════════ --}}
    <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" id="quizForm"
          x-data="{ submitted: false, answered: {} }"
          @submit="submitted = true">
        @csrf
        <input type="hidden" name="_attempt_id" value="{{ $attempt->id }}">

        <div class="space-y-5">
            @foreach($questions as $i => $question)
            <div class="card animate-fade-up" style="animation-delay:{{ .04 + $i * .03 }}s"
                 id="q{{ $question->id }}">

                {{-- Question Header --}}
                <div class="flex items-start gap-3 mb-4">
                    <span class="w-9 h-9 rounded-2xl bg-brand-500 text-white flex items-center justify-center font-black text-sm flex-shrink-0">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1">
                        <p class="font-bold text-main text-base leading-relaxed">
                            {{ $question->question_text }}
                        </p>
                        @if($question->question_image_url)
                        <img src="{{ $question->question_image_url }}"
                             class="mt-3 max-h-48 rounded-2xl object-contain bg-surface2" alt="">
                        @endif
                        @if($question->question_audio)
                        <audio controls class="mt-3 w-full">
                            <source src="{{ asset('storage/'.$question->question_audio) }}">
                        </audio>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge-brand text-xs">{{ $question->marks }} {{ __('teacher.marks') }}</span>
                    </div>
                </div>

                {{-- MCQ / True-False Options --}}
                @if(in_array($question->type, ['mcq','true_false']))
                <div class="space-y-2 ms-12"
                     x-data="{ selected: null }">
                    @foreach($question->options as $opt)
                    <label class="flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer select-none transition-all
                                  hover:border-brand-400 hover:bg-brand-50/50
                                  has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:shadow-glow"
                           @click="answered[{{ $question->id }}] = true">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opt->id }}"
                               class="w-4 h-4 accent-brand-500 flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <span class="text-sm text-main">{{ $opt->option_text }}</span>
                            @if($opt->option_image_url)
                            <img src="{{ $opt->option_image_url }}" class="mt-2 max-h-24 rounded-xl" alt="">
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Fill Blank --}}
                @elseif($question->type === 'fill_blank')
                <div class="ms-12">
                    <label class="label">{{ __('student.your_answer') }}</label>
                    <input type="text" name="text_answers[{{ $question->id }}]" class="input"
                           placeholder="{{ __('student.fill_blank_placeholder') }}"
                           @input="answered[{{ $question->id }}] = true">
                </div>

                {{-- Short Answer --}}
                @elseif($question->type === 'short_answer')
                <div class="ms-12">
                    <label class="label">{{ __('student.your_answer') }}</label>
                    <textarea name="text_answers[{{ $question->id }}]" rows="3"
                              class="input resize-none"
                              placeholder="{{ __('student.short_answer_placeholder') }}"
                              @input="answered[{{ $question->id }}] = true"></textarea>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- ══════════ Submit Button ══════════ --}}
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 animate-fade-up">
            <div class="text-sm text-muted">
                <span x-text="Object.keys(answered).length"></span> / {{ $questions->count() }} {{ __('student.answered') }}
            </div>
            <button type="submit"
                    :disabled="submitted"
                    :class="submitted ? 'opacity-60 cursor-not-allowed' : ''"
                    class="btn-primary !py-3.5 !px-10 text-base w-full sm:w-auto justify-center">
                <span x-show="!submitted" class="flex items-center gap-2">
                    ✅ {{ __('student.submit_quiz') }}
                </span>
                <span x-show="submitted" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ __('student.submitting') }}
                </span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function quizTimer(minutes) {
    return {
        timeDisplay: '',
        remaining: minutes * 60,
        init() {
            if (minutes <= 0) return;
            this.updateDisplay();
            const interval = setInterval(() => {
                this.remaining--;
                this.updateDisplay();
                if (this.remaining <= 0) {
                    clearInterval(interval);
                    document.getElementById('quizForm').submit();
                }
                if (this.remaining <= 60) {
                    // Flash warning when 1 min left
                    this.$el.querySelector('.text-warning-600')?.closest('.bg-warning-50')?.classList.add('animate-pulse-glow');
                }
            }, 1000);
        },
        updateDisplay() {
            const m = Math.floor(this.remaining / 60);
            const s = this.remaining % 60;
            this.timeDisplay = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }
    };
}
</script>
@endpush