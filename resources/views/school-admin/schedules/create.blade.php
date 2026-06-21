@extends('layouts.app')
@section('title', __('schooladmin.add_schedule'))
@section('page-title', __('schooladmin.add_schedule'))
@section('page-subtitle', __('schooladmin.add_schedule_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.schedules.store') }}" class="space-y-6"
          x-data="{ loading: false, isOnline: false }" @submit="loading = true">
        @csrf

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📅</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.schedule_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('app.classrooms') }} *</label>
                    <select name="classroom_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('app.subjects') }} *</label>
                    <select name="subject_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('app.teachers') }} *</label>
                    <select name="teacher_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.academic_year') }} *</label>
                    <select name="academic_year_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($years as $y)
                        <option value="{{ $y->id }}" {{ old('academic_year_id') == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.day_of_week') }} *</label>
                    <select name="day_of_week" required class="input">
                        @foreach([0=>'sunday',1=>'monday',2=>'tuesday',3=>'wednesday',4=>'thursday'] as $val=>$key)
                        <option value="{{ $val }}" {{ old('day_of_week') == $val ? 'selected' : '' }}>{{ __('app.'.$key) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.time_slot') }} *</label>
                    <select name="time_slot_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($timeSlots as $slot)
                        <option value="{{ $slot->id }}" {{ old('time_slot_id') == $slot->id ? 'selected' : '' }}>
                            {{ $slot->start_time }} – {{ $slot->end_time }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.room') }}</label>
                    <input type="text" name="room" value="{{ old('room') }}" class="input">
                </div>
                <div class="flex items-end pb-2.5">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="is_online" value="1" x-model="isOnline" class="w-4 h-4 rounded-lg accent-brand-500">
                        <span class="text-sm font-medium text-main">{{ __('schooladmin.is_online_class') }}</span>
                    </label>
                </div>
            </div>

            <div x-show="isOnline" x-cloak class="animate-fade">
                <label class="label">{{ __('schooladmin.meeting_link') }}</label>
                <input type="url" name="meeting_link" value="{{ old('meeting_link') }}" class="input" placeholder="https://meet.google.com/...">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('school-admin.schedules.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
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