@extends('layouts.app')
@section('title', __('teacher.new_quiz'))
@section('page-title', __('teacher.new_quiz'))
@section('page-subtitle', __('teacher.new_quiz_subtitle'))

@section('content')
<div class="max-w-3xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('teacher.quizzes.store') }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        {{-- ══════════ Basic Settings ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">⚙️</span>
                <h3 class="font-bold text-main">{{ __('teacher.quiz_settings') }}</h3>
            </div>

            @if(request('lesson_id'))
            <input type="hidden" name="lesson_id" value="{{ request('lesson_id') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="label">{{ __('teacher.quiz_title') }} *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="input" placeholder="{{ __('teacher.quiz_title_placeholder') }}">
                </div>

                {{-- Type --}}
                <div>
                    <label class="label">{{ __('teacher.quiz_type') }}</label>
                    <select name="type" class="input">
                        @foreach(['lesson_quiz'=>__('teacher.type_lesson_quiz'), 'unit_test'=>__('teacher.type_unit_test'), 'midterm'=>__('teacher.type_midterm'), 'final'=>__('teacher.type_final'), 'practice'=>__('teacher.type_practice')] as $v=>$l)
                        <option value="{{ $v }}" {{ old('type') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Subject --}}
                <div>
                    <label class="label">{{ __('app.subjects') }}</label>
                    <select name="subject_id" class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Total Marks --}}
                <div>
                    <label class="label">{{ __('teacher.total_marks') }} *</label>
                    <input type="number" name="total_marks" value="{{ old('total_marks', 100) }}"
                           min="1" required class="input">
                </div>

                {{-- Pass Marks --}}
                <div>
                    <label class="label">{{ __('teacher.pass_marks') }} *</label>
                    <input type="number" name="pass_marks" value="{{ old('pass_marks', 50) }}"
                           min="1" required class="input">
                </div>

                {{-- Duration --}}
                <div>
                    <label class="label">{{ __('teacher.duration_minutes') }}</label>
                    <div class="relative">
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}"
                               min="1" class="input ps-10">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none text-sm">⏱</span>
                    </div>
                </div>

                {{-- Max Attempts --}}
                <div>
                    <label class="label">{{ __('teacher.max_attempts') }}</label>
                    <input type="number" name="max_attempts" value="{{ old('max_attempts', 1) }}"
                           min="1" max="10" class="input">
                </div>

                {{-- Available From --}}
                <div>
                    <label class="label">{{ __('teacher.available_from') }}</label>
                    <input type="datetime-local" name="available_from" value="{{ old('available_from') }}" class="input">
                </div>

                {{-- Available Until --}}
                <div>
                    <label class="label">{{ __('teacher.available_until') }}</label>
                    <input type="datetime-local" name="available_until" value="{{ old('available_until') }}" class="input">
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="label">{{ __('teacher.quiz_description') }}</label>
                    <textarea name="description" rows="2" class="input resize-none"
                              placeholder="{{ __('teacher.quiz_description_placeholder') }}">{{ old('description') }}</textarea>
                </div>

                {{-- Instructions --}}
                <div class="md:col-span-2">
                    <label class="label">{{ __('teacher.quiz_instructions') }}</label>
                    <textarea name="instructions" rows="2" class="input resize-none"
                              placeholder="{{ __('teacher.quiz_instructions_placeholder') }}">{{ old('instructions') }}</textarea>
                </div>
            </div>

            {{-- Options --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 border-t border-bd">
                @foreach([
                    ['name'=>'shuffle_questions', 'label'=>__('teacher.shuffle_questions'), 'icon'=>'🔀'],
                    ['name'=>'shuffle_options',   'label'=>__('teacher.shuffle_options'),   'icon'=>'🔁'],
                    ['name'=>'show_results_immediately', 'label'=>__('teacher.show_results_immediately'), 'icon'=>'⚡', 'checked'=>true],
                    ['name'=>'show_correct_answers',     'label'=>__('teacher.show_correct_answers'),     'icon'=>'✅'],
                ] as $opt)
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-bd hover:bg-hover cursor-pointer select-none transition">
                    <input type="checkbox" name="{{ $opt['name'] }}" value="1"
                           {{ ($opt['checked'] ?? false) || old($opt['name']) ? 'checked' : '' }}
                           class="w-4 h-4 rounded-lg accent-brand-500">
                    <span class="text-xs font-medium text-main">{{ $opt['icon'] }} {{ $opt['label'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- ══════════ Actions ══════════ --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <a href="{{ route('teacher.quizzes.index') }}" class="btn-outline w-full sm:w-auto justify-center">
                {{ __('app.cancel') }}
            </a>
            <div class="flex gap-3 w-full sm:w-auto">
                <button type="submit" name="status" value="draft" class="btn-outline flex-1 sm:flex-none justify-center">
                    💾 {{ __('teacher.save_draft') }}
                </button>
                <button type="submit" name="status" value="published" :disabled="loading"
                        class="btn-primary flex-1 sm:flex-none justify-center">
                    <span x-show="!loading">🚀 {{ __('teacher.quiz_create_and_add_questions') }}</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ __('app.loading') }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection