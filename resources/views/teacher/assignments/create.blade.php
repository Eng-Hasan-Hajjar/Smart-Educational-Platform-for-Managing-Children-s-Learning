@extends('layouts.app')
@section('title', __('teacher.new_assignment'))
@section('page-title', __('teacher.new_assignment'))
@section('page-subtitle', __('teacher.new_assignment_subtitle'))

@section('content')
<div class="max-w-3xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('teacher.assignments.store') }}"
          enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false, subType: '{{ old('submission_type', 'both') }}' }"
          @submit="loading = true">
        @csrf

        {{-- ══════════ Basic Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📋</span>
                <h3 class="font-bold text-main">{{ __('teacher.assignment_info') }}</h3>
            </div>

            {{-- Subject --}}
            <div>
                <label class="label">{{ __('app.subjects') }} *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">📚</span>
                    <select name="subject_id" class="input ps-10" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Classroom --}}
            <div>
                <label class="label">{{ __('app.classrooms') }} *</label>
                <div class="relative">
                    <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">🏛️</span>
                    <select name="classroom_id" class="input ps-10" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} — {{ $c->academicLevel->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Title --}}
            <div>
                <label class="label">{{ __('teacher.assignment_title') }} *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="input" placeholder="{{ __('teacher.assignment_title_placeholder') }}">
            </div>

            {{-- Description --}}
            <div>
                <label class="label">{{ __('teacher.assignment_description') }} *</label>
                <textarea name="description" rows="4" required class="input resize-none"
                          placeholder="{{ __('teacher.assignment_description_placeholder') }}">{{ old('description') }}</textarea>
            </div>

            {{-- Instructions --}}
            <div>
                <label class="label">{{ __('teacher.assignment_instructions') }}</label>
                <textarea name="instructions" rows="2" class="input resize-none"
                          placeholder="{{ __('teacher.assignment_instructions_placeholder') }}">{{ old('instructions') }}</textarea>
            </div>
        </div>

        {{-- ══════════ Settings ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">⚙️</span>
                <h3 class="font-bold text-main">{{ __('teacher.assignment_settings') }}</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Total Marks --}}
                <div>
                    <label class="label">{{ __('teacher.total_marks') }}</label>
                    <input type="number" name="total_marks" value="{{ old('total_marks', 100) }}"
                           min="1" class="input">
                </div>

                {{-- Due Date --}}
                <div>
                    <label class="label">{{ __('teacher.due_date') }} *</label>
                    <input type="datetime-local" name="due_date" value="{{ old('due_date') }}" required class="input">
                </div>

                {{-- Submission Type --}}
                <div>
                    <label class="label">{{ __('teacher.submission_type') }}</label>
                    <select name="submission_type" x-model="subType" class="input">
                        <option value="text">📝 {{ __('teacher.submission_text') }}</option>
                        <option value="file">📁 {{ __('teacher.submission_file') }}</option>
                        <option value="both" selected>📝📁 {{ __('teacher.submission_both') }}</option>
                    </select>
                </div>

                {{-- Max File Size --}}
                <div x-show="subType === 'file' || subType === 'both'">
                    <label class="label">{{ __('teacher.max_file_size') }}</label>
                    <div class="relative">
                        <input type="number" name="max_file_size_mb" value="{{ old('max_file_size_mb', 10) }}"
                               min="1" max="100" class="input pe-14">
                        <span class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-faint text-sm pointer-events-none">MB</span>
                    </div>
                </div>
            </div>

            {{-- Late Submission --}}
            <div class="rounded-2xl border border-bd p-4 space-y-3"
                 x-data="{ allowLate: {{ old('allow_late_submission') ? 'true' : 'false' }} }">
                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                    <input type="checkbox" name="allow_late_submission" value="1"
                           x-model="allowLate"
                           class="w-4 h-4 rounded-lg accent-brand-500">
                    <span class="text-sm font-medium text-main">{{ __('teacher.allow_late_submission') }}</span>
                </label>
                <div x-show="allowLate" x-cloak class="animate-fade">
                    <label class="label">{{ __('teacher.late_penalty_percent') }}</label>
                    <div class="relative">
                        <input type="number" name="late_penalty_percent"
                               value="{{ old('late_penalty_percent', 10) }}"
                               min="0" max="100" class="input pe-8">
                        <span class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-faint text-sm pointer-events-none">%</span>
                    </div>
                    <p class="text-xs text-muted mt-1.5">{{ __('teacher.late_penalty_hint') }}</p>
                </div>
            </div>

            {{-- Attachment --}}
            <div>
                <label class="label">{{ __('teacher.assignment_attachment') }}</label>
                <div class="relative border-2 border-dashed border-bd rounded-2xl p-5 text-center hover:border-brand-400 transition cursor-pointer"
                     x-data="{ fname: '' }">
                    <input type="file" name="attachment" class="absolute inset-0 opacity-0 cursor-pointer"
                           @change="fname = $event.target.files[0]?.name ?? ''">
                    <div x-show="!fname">
                        <span class="text-3xl">📎</span>
                        <p class="text-muted text-sm mt-2">{{ __('teacher.drag_drop_file') }}</p>
                        <p class="text-faint text-xs mt-1">PDF, DOCX, XLSX ... {{ __('teacher.max_size') }} 20MB</p>
                    </div>
                    <div x-show="fname" x-cloak>
                        <span class="text-3xl">✅</span>
                        <p class="text-success-600 font-bold text-sm mt-1" x-text="fname"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ Actions ══════════ --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <a href="{{ route('teacher.assignments.index') }}" class="btn-outline w-full sm:w-auto justify-center">
                {{ __('app.cancel') }}
            </a>
            <div class="flex gap-3 w-full sm:w-auto">
                <button type="submit" name="status" value="draft" class="btn-outline flex-1 sm:flex-none justify-center">
                    💾 {{ __('teacher.save_draft') }}
                </button>
                <button type="submit" name="status" value="published" :disabled="loading"
                        class="btn-primary flex-1 sm:flex-none justify-center">
                    <span x-show="!loading">🚀 {{ __('teacher.publish_assignment') }}</span>
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