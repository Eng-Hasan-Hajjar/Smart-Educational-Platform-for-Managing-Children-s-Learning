@extends('layouts.app')
@section('title', __('teacher.new_lesson'))
@section('page-title', __('teacher.new_lesson'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-up">
    <div class="card">
        <form method="POST" action="{{ route('teacher.lessons.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">

                {{-- الوحدة --}}
                <div>
                    <label class="label">{{ __('app.unit') }} <span class="text-danger-500">*</span></label>
                    <select name="unit_id" class="input" required>
                        <option value="">{{ __('app.select') }}...</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->subject->name ?? '' }} → {{ $unit->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('unit_id') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- العنوان --}}
                <div>
                    <label class="label">{{ __('app.title') }} <span class="text-danger-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="input" required>
                    @error('title') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- الوصف --}}
                <div>
                    <label class="label">{{ __('app.description') }}</label>
                    <textarea name="description" rows="3" class="input">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- المدة --}}
                    <div>
                        <label class="label">{{ __('teacher.duration_minutes') }}</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 30) }}" class="input" min="1">
                    </div>

                    {{-- الترتيب --}}
                    <div>
                        <label class="label">{{ __('app.order') }}</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" class="input" min="0">
                    </div>
                </div>

                {{-- صورة مصغرة --}}
                <div>
                    <label class="label">{{ __('teacher.thumbnail') }}</label>
                    <input type="file" name="thumbnail" accept="image/*" class="input">
                    @error('thumbnail') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- أهداف الدرس --}}
                <div x-data="{ objectives: {{ json_encode(old('objectives', [''])) }} }">
                    <label class="label">{{ __('teacher.objectives') }}</label>
                    <template x-for="(obj, idx) in objectives" :key="idx">
                        <div class="flex gap-2 mb-2">
                            <input type="text" :name="'objectives['+idx+']'" x-model="objectives[idx]" class="input flex-1"
                                   placeholder="{{ __('teacher.objective_placeholder') }}">
                            <button type="button" @click="objectives.splice(idx, 1)" class="text-danger-500 hover:text-danger-700 px-2" x-show="objectives.length > 1">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="objectives.push('')" class="text-brand-500 hover:text-brand-700 text-sm font-bold mt-1">
                        + {{ __('teacher.add_objective') }}
                    </button>
                </div>

                {{-- خيارات --}}
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_free" value="1" class="rounded border-gray-300 text-brand-500" {{ old('is_free') ? 'checked' : '' }}>
                        <span class="text-sm text-main">{{ __('teacher.free_lesson') }}</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="allow_download" value="1" class="rounded border-gray-300 text-brand-500" {{ old('allow_download') ? 'checked' : '' }}>
                        <span class="text-sm text-main">{{ __('teacher.allow_download') }}</span>
                    </label>
                </div>

                {{-- الحالة --}}
                <div>
                    <label class="label">{{ __('app.status') }}</label>
                    <select name="status" class="input sm:w-48">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>{{ __('status.draft') }}</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>{{ __('status.published') }}</option>
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-bd">
                <a href="{{ route('teacher.lessons.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
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