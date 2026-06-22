@extends('layouts.app')
@section('title', __('teacher.new_unit'))
@section('page-title', __('teacher.new_unit'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-up">
    <div class="card">
        <form method="POST" action="{{ route('teacher.units.store') }}">
            @csrf
            <div class="space-y-5">

                {{-- المادة --}}
                <div>
                    <label class="label">{{ __('app.subject') }} <span class="text-danger-500">*</span></label>
                    <select name="subject_id" class="input" required>
                        <option value="">{{ __('app.select') }}...</option>
                        @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- اسم الوحدة (عربي) --}}
                <div>
                    <label class="label">{{ __('app.name') }} ({{ __('app.arabic') }}) <span class="text-danger-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input" required>
                    @error('name') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- اسم الوحدة (إنجليزي) --}}
                <div>
                    <label class="label">{{ __('app.name') }} ({{ __('app.english') }})</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" class="input">
                </div>

                {{-- الوصف --}}
                <div>
                    <label class="label">{{ __('app.description') }}</label>
                    <textarea name="description" rows="3" class="input">{{ old('description') }}</textarea>
                </div>

                {{-- الترتيب --}}
                <div>
                    <label class="label">{{ __('app.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" class="input sm:w-32" min="0">
                </div>

                {{-- نشر --}}
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:bg-brand-500 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"></div>
                    </label>
                    <span class="text-sm text-main">{{ __('teacher.publish_now') }}</span>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-bd">
                <a href="{{ route('teacher.units.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('app.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection