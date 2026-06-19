@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $quiz->title)
@section('page-title', __('app.edit') . ' — ' . $quiz->title)
@section('page-subtitle', __('teacher.type_'.$quiz->type) . ($quiz->subject ? ' · '.$quiz->subject->name : ''))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- ══════════ Tabs ══════════ --}}
    <div class="card !p-2 animate-fade-up" x-data="{ tab: 'settings' }">
        <div class="flex gap-1 overflow-x-auto">
            @foreach([
                ['key'=>'settings',  'icon'=>'⚙️',  'label'=>__('teacher.tab_settings')],
                ['key'=>'questions', 'icon'=>'❓',  'label'=>__('teacher.tab_questions') . ' (' . $quiz->questions->count() . ')'],
                ['key'=>'results',   'icon'=>'📊',  'label'=>__('teacher.tab_results')],
            ] as $t)
            <button @click="tab = '{{ $t['key'] }}'"
                    :class="tab==='{{ $t['key'] }}'
                        ? 'bg-brand-500 text-white shadow-glow'
                        : 'text-muted hover:bg-hover'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap flex-1 justify-center">
                <span>{{ $t['icon'] }}</span>
                <span>{{ $t['label'] }}</span>
            </button>
            @endforeach
        </div>

        {{-- ──── Tab: Settings ──── --}}
        <div x-show="tab==='settings'" x-cloak class="mt-4 animate-fade">
            <form method="POST" action="{{ route('teacher.quizzes.update', $quiz) }}"
                  class="space-y-5" x-data="{loading:false}" @submit="loading=true">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="label">{{ __('teacher.quiz_title') }} *</label>
                        <input type="text" name="title" value="{{ old('title', $quiz->title) }}" required class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('teacher.total_marks') }} *</label>
                        <input type="number" name="total_marks" value="{{ old('total_marks', $quiz->total_marks) }}" min="1" required class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('teacher.pass_marks') }} *</label>
                        <input type="number" name="pass_marks" value="{{ old('pass_marks', $quiz->pass_marks) }}" min="1" required class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('teacher.duration_minutes') }}</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes) }}" class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('teacher.max_attempts') }}</label>
                        <input type="number" name="max_attempts" value="{{ old('max_attempts', $quiz->max_attempts) }}" min="1" class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('teacher.available_from') }}</label>
                        <input type="datetime-local" name="available_from"
                               value="{{ old('available_from', $quiz->available_from?->format('Y-m-d\TH:i')) }}" class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('teacher.available_until') }}</label>
                        <input type="datetime-local" name="available_until"
                               value="{{ old('available_until', $quiz->available_until?->format('Y-m-d\TH:i')) }}" class="input">
                    </div>
                    <div class="md:col-span-2">
                        <label class="label">{{ __('teacher.quiz_instructions') }}</label>
                        <textarea name="instructions" rows="2" class="input resize-none">{{ old('instructions', $quiz->instructions) }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 border-t border-bd">
                    @foreach([
                        ['name'=>'shuffle_questions', 'label'=>__('teacher.shuffle_questions'), 'icon'=>'🔀', 'val'=>$quiz->shuffle_questions],
                        ['name'=>'shuffle_options',   'label'=>__('teacher.shuffle_options'),   'icon'=>'🔁', 'val'=>$quiz->shuffle_options],
                        ['name'=>'show_results_immediately', 'label'=>__('teacher.show_results_immediately'), 'icon'=>'⚡', 'val'=>$quiz->show_results_immediately],
                        ['name'=>'show_correct_answers',     'label'=>__('teacher.show_correct_answers'),     'icon'=>'✅', 'val'=>$quiz->show_correct_answers],
                    ] as $opt)
                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-bd hover:bg-hover cursor-pointer select-none transition">
                        <input type="checkbox" name="{{ $opt['name'] }}" value="1"
                               {{ $opt['val'] ? 'checked' : '' }}
                               class="w-4 h-4 rounded-lg accent-brand-500">
                        <span class="text-xs font-medium text-main">{{ $opt['icon'] }} {{ $opt['label'] }}</span>
                    </label>
                    @endforeach
                </div>

                <div class="flex items-center justify-between gap-3 pt-2 border-t border-bd">
                    <div class="flex gap-3">
                        <button type="submit" name="status" value="draft" class="btn-outline">
                            💾 {{ __('teacher.save_draft') }}
                        </button>
                        <button type="submit" name="status" value="published" :disabled="loading" class="btn-primary">
                            <span x-show="!loading">🚀 {{ __('teacher.publish') }}</span>
                            <span x-show="loading" x-cloak class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                {{ __('app.loading') }}
                            </span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('teacher.quizzes.destroy', $quiz) }}"
                          onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">
                            🗑️ {{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </form>
        </div>

        {{-- ──── Tab: Questions ──── --}}
        <div x-show="tab==='questions'" x-cloak class="mt-4 space-y-4 animate-fade">

            {{-- Existing Questions --}}
            @forelse($quiz->questions as $i => $question)
            <div class="p-4 rounded-2xl border border-bd bg-surface2 animate-slide">
                <div class="flex items-start gap-3">
                    <span class="w-8 h-8 rounded-xl bg-brand-500 text-white flex items-center justify-center text-sm font-black flex-shrink-0">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-main leading-relaxed">{{ $question->question_text }}</p>
                        <div class="flex items-center gap-3 mt-1 text-xs text-muted">
                            <span>{{ __('teacher.type_'.$question->type) }}</span>
                            <span>·</span>
                            <span class="font-bold text-brand-500">{{ $question->marks }} {{ __('teacher.marks') }}</span>
                        </div>

                        {{-- Options --}}
                        @if($question->options->count())
                        <div class="mt-3 space-y-1.5">
                            @foreach($question->options as $opt)
                            <div class="flex items-center gap-2 text-sm
                                        {{ $opt->is_correct ? 'text-success-600 font-bold' : 'text-muted' }}">
                                <span class="w-5 h-5 rounded-lg flex items-center justify-center text-xs flex-shrink-0
                                             {{ $opt->is_correct ? 'bg-success-50 text-success-600' : 'bg-hover text-faint' }}">
                                    {{ $opt->is_correct ? '✓' : '○' }}
                                </span>
                                {{ $opt->option_text }}
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($question->explanation)
                        <div class="mt-2 p-2.5 rounded-xl bg-info-50 border border-info-500/20 text-xs text-info-600">
                            💡 {{ $question->explanation }}
                        </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('teacher.quizzes.questions.destroy', [$quiz, $question]) }}"
                          onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-danger-600 hover:text-danger-500 transition p-1.5 rounded-lg hover:bg-danger-50">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8 bg-surface2 rounded-2xl border-2 border-dashed border-bd animate-fade">
                <span class="text-4xl animate-float inline-block">❓</span>
                <p class="text-muted text-sm mt-2 font-bold">{{ __('teacher.no_questions_yet') }}</p>
                <p class="text-faint text-xs mt-1">{{ __('teacher.add_questions_below') }}</p>
            </div>
            @endforelse

            {{-- Add Question Form --}}
            <div class="card !bg-surface2 !border-dashed"
                 x-data="{
                     qtype: 'mcq',
                     options: [
                         { text: '', correct: false },
                         { text: '', correct: false },
                         { text: '', correct: false },
                         { text: '', correct: false },
                     ],
                     correctIndex: null,
                     addOption()  { if(this.options.length < 6) this.options.push({ text:'', correct:false }); },
                     removeOption(i){ if(this.options.length > 2) this.options.splice(i,1); },
                     setCorrect(i){ this.correctIndex = i; },
                     isMcqLike(){ return ['mcq','true_false'].includes(this.qtype); },
                     isFillBlank(){ return this.qtype === 'fill_blank'; },
                     init(){
                         this.$watch('qtype', v=>{
                             if(v==='true_false'){
                                 this.options=[{text:'{{ __('teacher.true') }}',correct:false},{text:'{{ __('teacher.false') }}',correct:false}];
                             } else if(v==='mcq' && this.options.length < 2){
                                 this.options=[{text:'',correct:false},{text:'',correct:false},{text:'',correct:false},{text:'',correct:false}];
                             }
                         });
                     }
                 }">

                <div class="flex items-center gap-3 mb-4">
                    <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">➕</span>
                    <h4 class="font-bold text-main">{{ __('teacher.add_question') }}</h4>
                </div>

                <form method="POST" action="{{ route('teacher.quizzes.questions.store', $quiz) }}"
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label">{{ __('teacher.question_type') }}</label>
                            <select name="type" x-model="qtype" class="input">
                                <option value="mcq">{{ __('teacher.type_mcq') }}</option>
                                <option value="true_false">{{ __('teacher.type_true_false') }}</option>
                                <option value="fill_blank">{{ __('teacher.type_fill_blank') }}</option>
                                <option value="short_answer">{{ __('teacher.type_short_answer') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">{{ __('teacher.question_marks') }}</label>
                            <input type="number" name="marks" value="1" min="1" class="input">
                        </div>
                    </div>

                    {{-- Question Text --}}
                    <div>
                        <label class="label">{{ __('teacher.question_text') }} *</label>
                        <textarea name="question_text" rows="3" required class="input resize-none"
                                  placeholder="{{ __('teacher.question_text_placeholder') }}"></textarea>
                    </div>

                    {{-- Question Image --}}
                    <div>
                        <label class="label">{{ __('teacher.question_image_opt') }}</label>
                        <input type="file" name="question_image" accept="image/*" class="input !py-2.5">
                    </div>

                    {{-- MCQ / True-False Options --}}
                    <div x-show="isMcqLike()">
                        <label class="label">{{ __('teacher.answer_options') }}</label>
                        <div class="space-y-2">
                            <template x-for="(opt, i) in options" :key="i">
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="setCorrect(i)"
                                            :class="correctIndex===i ? 'bg-success-500 text-white border-success-500' : 'bg-surface border-bd text-muted hover:border-success-400'"
                                            class="w-8 h-8 rounded-xl border-2 flex items-center justify-center text-sm font-black flex-shrink-0 transition">
                                        ✓
                                    </button>
                                    <input type="text" :name="'options['+i+'][text]'" x-model="opt.text"
                                           class="input flex-1" :placeholder="'{{ __('teacher.option') }} ' + (i+1)">
                                    <input type="hidden" :name="'options['+i+'][correct]'" :value="correctIndex===i ? 1 : 0">
                                    <button type="button" @click="removeOption(i)"
                                            x-show="options.length > 2 && qtype !== 'true_false'"
                                            class="w-8 h-8 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 flex items-center justify-center flex-shrink-0 transition">
                                        ×
                                    </button>
                                </div>
                            </template>
                        </div>

                        {{-- hidden input for correct_option index --}}
                        <input type="hidden" name="correct_option" :value="correctIndex">

                        <button type="button" @click="addOption()"
                                x-show="qtype === 'mcq' && options.length < 6"
                                class="mt-2 text-xs font-bold text-brand-500 hover:text-brand-700 transition flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('teacher.add_option') }}
                        </button>
                    </div>

                    {{-- Fill Blank Answer --}}
                    <div x-show="isFillBlank()" x-cloak>
                        <label class="label">{{ __('teacher.correct_answer') }} *</label>
                        <input type="text" name="correct_answer" class="input"
                               placeholder="{{ __('teacher.correct_answer_placeholder') }}">
                    </div>

                    {{-- Explanation --}}
                    <div>
                        <label class="label">{{ __('teacher.explanation_opt') }}</label>
                        <textarea name="explanation" rows="2" class="input resize-none"
                                  placeholder="{{ __('teacher.explanation_placeholder') }}"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            ➕ {{ __('teacher.add_question') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ──── Tab: Results ──── --}}
        <div x-show="tab==='results'" x-cloak class="mt-4 animate-fade">
            @if($quiz->attempts_count > 0)
            {{-- Summary Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5 stagger">
                @php
                    $attempts     = $quiz->attempts()->with('student')->latest()->get();
                    $passedCount  = $attempts->where('is_passed', true)->count();
                    $avgScore     = round($attempts->avg('percentage') ?? 0, 1);
                    $highScore    = round($attempts->max('percentage') ?? 0, 1);
                    $passRate     = $attempts->count() > 0 ? round($passedCount / $attempts->count() * 100) : 0;
                @endphp
                @foreach([
                    ['label'=>__('teacher.attempts_total'), 'value'=>$attempts->count(), 'icon'=>'👥', 'ring'=>'brand'],
                    ['label'=>__('teacher.pass_rate'),      'value'=>$passRate.'%',       'icon'=>'✅', 'ring'=>'success'],
                    ['label'=>__('teacher.avg_score'),      'value'=>$avgScore.'%',        'icon'=>'📊', 'ring'=>'info'],
                    ['label'=>__('teacher.highest_score'),  'value'=>$highScore.'%',       'icon'=>'🏆', 'ring'=>'warning'],
                ] as $s)
                <div class="card !p-3 text-center">
                    <div class="w-9 h-9 rounded-xl mx-auto mb-2 flex items-center justify-center text-lg bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600">
                        {{ $s['icon'] }}
                    </div>
                    <p class="text-xl font-black text-main">{{ $s['value'] }}</p>
                    <p class="text-faint text-xs mt-0.5">{{ $s['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Results Table --}}
            <div class="overflow-x-auto rounded-2xl border border-bd">
                <table class="w-full text-sm">
                    <thead class="bg-surface2 border-b border-bd">
                        <tr>
                            <th class="text-start py-3 px-4 text-muted font-semibold">{{ __('counselor.student') }}</th>
                            <th class="text-start py-3 px-4 text-muted font-semibold">{{ __('teacher.score') }}</th>
                            <th class="text-start py-3 px-4 text-muted font-semibold">{{ __('teacher.percentage') }}</th>
                            <th class="text-start py-3 px-4 text-muted font-semibold">{{ __('teacher.status') }}</th>
                            <th class="text-start py-3 px-4 text-muted font-semibold">{{ __('teacher.attempt_date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-bd">
                        @foreach($attempts->take(20) as $attempt)
                        <tr class="hover:bg-hover transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $attempt->student->avatar_url }}" class="w-8 h-8 rounded-full object-cover" alt="">
                                    <span class="font-medium text-main">{{ $attempt->student->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-main font-bold">
                                {{ $attempt->total_marks_obtained }}/{{ $quiz->total_marks }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 progress-track">
                                        <div class="progress-fill !bg-none"
                                             style="width: {{ $attempt->percentage }}%;
                                                    background: {{ $attempt->is_passed ? 'var(--success-500)' : 'var(--danger-500)' }}">
                                        </div>
                                    </div>
                                    <span class="font-bold text-xs {{ $attempt->is_passed ? 'text-success-600' : 'text-danger-600' }}">
                                        {{ round($attempt->percentage) }}%
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge-{{ $attempt->is_passed ? 'green' : 'red' }}">
                                    {{ $attempt->is_passed ? __('teacher.passed') : __('teacher.failed') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-muted text-xs">
                                {{ $attempt->submitted_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12 animate-fade">
                <span class="text-5xl animate-float inline-block">📊</span>
                <p class="text-muted text-sm mt-3 font-bold">{{ __('teacher.no_attempts_yet') }}</p>
                <p class="text-faint text-xs mt-1">{{ __('teacher.no_attempts_hint') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection