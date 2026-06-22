@extends('layouts.app')
@section('title', __('app.edit') . ' - ' . $unit->name)
@section('page-title', __('app.edit_unit'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-up">
    <div class="card">
        <form method="POST" action="{{ route('teacher.units.update', $unit) }}">
            @csrf @method('PUT')
            <div class="space-y-5">

                <div>
                    <label class="label">{{ __('app.subject') }} <span class="text-danger-500">*</span></label>
                    <select name="subject_id" class="input" required>
                        @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id', $unit->subject_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">{{ __('app.name') }} ({{ __('app.arabic') }}) <span class="text-danger-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $unit->name) }}" class="input" required>
                    @error('name') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">{{ __('app.name') }} ({{ __('app.english') }})</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $unit->name_en) }}" class="input">
                </div>

                <div>
                    <label class="label">{{ __('app.description') }}</label>
                    <textarea name="description" rows="3" class="input">{{ old('description', $unit->description) }}</textarea>
                </div>

                <div>
                    <label class="label">{{ __('app.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', $unit->order) }}" class="input sm:w-32" min="0">
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $unit->is_published) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-brand-300 rounded-full peer peer-checked:bg-brand-500 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"></div>
                    </label>
                    <span class="text-sm text-main">{{ __('teacher.publish_now') }}</span>
                </div>

            </div>

            <div class="flex items-center justify-between gap-3 mt-6 pt-5 border-t border-bd">
                <form method="POST" action="{{ route('teacher.units.destroy', $unit) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-100 transition">🗑️ {{ __('app.delete') }}</button>
                </form>
                <div class="flex gap-3">
                    <a href="{{ route('teacher.units.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
                    <button type="submit" class="btn-primary">{{ __('app.save') }}</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Lessons in this unit --}}
    @if($unit->lessons->count())
    <div class="card animate-fade-up" style="animation-delay:.1s">
        <h3 class="font-bold text-main mb-4 flex items-center gap-2">
            <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📖</span>
            {{ __('teacher.lessons_in_unit') }} ({{ $unit->lessons->count() }})
        </h3>
        @foreach($unit->lessons->sortBy('order') as $lesson)
        <a href="{{ route('teacher.lessons.edit', $lesson) }}"
           class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:border-brand-400 transition mb-2 group">
            <span class="w-8 h-8 rounded-lg bg-hover flex items-center justify-center text-sm font-bold text-faint flex-shrink-0">{{ $lesson->order }}</span>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm text-main truncate group-hover:text-brand-500 transition">{{ $lesson->title }}</p>
                <p class="text-xs text-faint">{{ $lesson->duration_minutes }} {{ __('teacher.minutes') }}</p>
            </div>
            <span class="badge-{{ $lesson->status === 'published' ? 'green' : 'yellow' }} flex-shrink-0">
                {{ __('status.'.$lesson->status) }}
            </span>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection