@extends('layouts.app')
@section('title', __('schooladmin.add_classroom'))
@section('page-title', __('schooladmin.add_classroom'))
@section('page-subtitle', __('schooladmin.add_classroom_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.classrooms.store') }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">🏛️</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.classroom_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.classroom_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input"
                           placeholder="{{ __('schooladmin.classroom_name_placeholder') }}">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.section') }}</label>
                    <input type="text" name="section" value="{{ old('section') }}" class="input"
                           placeholder="{{ __('schooladmin.section_placeholder') }}">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.academic_level') }} *</label>
                    <select name="academic_level_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('academic_level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.academic_year') }} *</label>
                    <select name="academic_year_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($years as $year)
                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.capacity') }} *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 30) }}" required min="1" max="100" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.room_number') }}</label>
                    <input type="text" name="room_number" value="{{ old('room_number') }}" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('schooladmin.classroom_description') }}</label>
                    <textarea name="description" rows="2" class="input resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
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
    </form>
</div>
@endsection