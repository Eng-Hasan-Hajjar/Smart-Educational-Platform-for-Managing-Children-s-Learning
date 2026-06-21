@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $classroom->name)
@section('page-title', __('app.edit') . ' — ' . $classroom->name)
@section('page-subtitle', __('schooladmin.edit_classroom_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.classrooms.update', $classroom) }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf @method('PUT')

        <div class="card space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-bd">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">🏛️</span>
                    <h3 class="font-bold text-main">{{ __('schooladmin.classroom_info') }}</h3>
                </div>
                <span class="badge-brand">{{ $classroom->students()->count() }} {{ __('teacher.students_total') }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.classroom_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $classroom->name) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.section') }}</label>
                    <input type="text" name="section" value="{{ old('section', $classroom->section) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.capacity') }} *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $classroom->capacity) }}" required min="1" max="100" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.room_number') }}</label>
                    <input type="text" name="room_number" value="{{ old('room_number', $classroom->room_number) }}" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('schooladmin.classroom_description') }}</label>
                    <textarea name="description" rows="2" class="input resize-none">{{ old('description', $classroom->description) }}</textarea>
                </div>
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer select-none pt-2 border-t border-bd">
                <input type="checkbox" name="is_active" value="1" {{ $classroom->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded-lg accent-brand-500">
                <span class="text-sm font-medium text-main">{{ __('schooladmin.classroom_active') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-3">
                <a href="{{ route('school-admin.classrooms.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
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
            <form method="POST" action="{{ route('school-admin.classrooms.destroy', $classroom) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑️ {{ __('app.delete') }}</button>
            </form>
        </div>
    </form>
</div>
@endsection