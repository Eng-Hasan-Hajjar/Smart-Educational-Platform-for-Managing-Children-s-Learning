@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $subject->name)
@section('page-title', __('app.edit') . ' — ' . $subject->name)
@section('page-subtitle', __('schooladmin.edit_subject_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.subjects.update', $subject) }}" class="space-y-6"
          x-data="{ loading: false, icon: '{{ old('icon', $subject->icon) }}', color: '{{ old('color', $subject->color) }}' }"
          @submit="loading = true">
        @csrf @method('PUT')

        <div class="card space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-bd">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📚</span>
                    <h3 class="font-bold text-main">{{ __('schooladmin.subject_info') }}</h3>
                </div>
                <span class="badge-info">{{ $subject->units()->count() }} {{ __('schooladmin.units_count') }}</span>
            </div>

            {{-- Icon + Color Preview --}}
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 transition-colors"
                     :style="`background: ${color}1A; color: ${color}`">
                    <span x-text="icon"></span>
                </div>
                <div class="flex-1 grid grid-cols-2 gap-3">
                    <div>
                        <label class="label">{{ __('schooladmin.subject_icon') }}</label>
                        <input type="text" name="icon" x-model="icon" maxlength="4" class="input text-center text-xl">
                    </div>
                    <div>
                        <label class="label">{{ __('schooladmin.subject_color') }}</label>
                        <input type="color" name="color" x-model="color" class="input !p-1 h-[42px] cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.subject_name_ar') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $subject->name) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.subject_name_en') }}</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $subject->name_en) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.academic_level') }} *</label>
                    <select name="academic_level_id" required class="input">
                        @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('academic_level_id', $subject->academic_level_id) == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.subject_code') }}</label>
                    <input type="text" name="code" value="{{ old('code', $subject->code) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.weekly_hours') }} *</label>
                    <input type="number" name="weekly_hours" value="{{ old('weekly_hours', $subject->weekly_hours) }}" required min="1" max="20" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.display_order') }}</label>
                    <input type="number" name="order" value="{{ old('order', $subject->order) }}" min="0" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('schooladmin.subject_description') }}</label>
                    <textarea name="description" rows="3" class="input resize-none">{{ old('description', $subject->description) }}</textarea>
                </div>
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer select-none pt-2 border-t border-bd">
                <input type="checkbox" name="is_active" value="1" {{ $subject->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded-lg accent-brand-500">
                <span class="text-sm font-medium text-main">{{ __('schooladmin.subject_active') }}</span>
            </label>
        </div>

        @if($subject->units()->exists())
        <div class="card !p-4 border-warning-500/30 bg-warning-50 flex items-center gap-3">
            <span class="text-2xl flex-shrink-0">⚠️</span>
            <p class="text-sm text-warning-700">{{ __('schooladmin.subject_has_content_warning') }}</p>
        </div>
        @endif

        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-3">
                <a href="{{ route('school-admin.subjects.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
                <button type="submit" :disabled="loading" class="btn-primary">
                    <span x-show="!loading">💾 {{ __('app.save') }}</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ __('app.loading') }}
                    </span>
                </button>
            </div>
            <form method="POST" action="{{ route('school-admin.subjects.destroy', $subject) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑️ {{ __('app.delete') }}</button>
            </form>
        </div>
    </form>
</div>
@endsection