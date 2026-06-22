@extends('layouts.app')
@section('title', __('app.edit') . ' - ' . $lesson->title)
@section('page-title', __('app.edit_lesson'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-up">
    <div class="card">
        <form method="POST" action="{{ route('teacher.lessons.update', $lesson) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="space-y-5">

                {{-- الوحدة --}}
                <div>
                    <label class="label">{{ __('app.unit') }} <span class="text-danger-500">*</span></label>
                    <select name="unit_id" class="input" required>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id', $lesson->unit_id) == $unit->id ? 'selected' : '' }}>
                            {{ $unit->subject->name ?? '' }} → {{ $unit->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('unit_id') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- العنوان --}}
                <div>
                    <label class="label">{{ __('app.title') }} <span class="text-danger-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}" class="input" required>
                    @error('title') <p class="text-danger-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- الوصف --}}
                <div>
                    <label class="label">{{ __('app.description') }}</label>
                    <textarea name="description" rows="3" class="input">{{ old('description', $lesson->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">{{ __('teacher.duration_minutes') }}</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" class="input" min="1">
                    </div>
                    <div>
                        <label class="label">{{ __('app.order') }}</label>
                        <input type="number" name="order" value="{{ old('order', $lesson->order) }}" class="input" min="0">
                    </div>
                </div>

                {{-- صورة مصغرة --}}
                <div>
                    <label class="label">{{ __('teacher.thumbnail') }}</label>
                    @if($lesson->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$lesson->thumbnail) }}" class="w-32 h-20 object-cover rounded-xl" alt="">
                    </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="input">
                </div>

                {{-- أهداف --}}
                <div x-data="{ objectives: {{ json_encode(old('objectives', $lesson->objectives ?? [''])) }} }">
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
                        <input type="checkbox" name="is_free" value="1" class="rounded border-gray-300 text-brand-500" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                        <span class="text-sm text-main">{{ __('teacher.free_lesson') }}</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="allow_download" value="1" class="rounded border-gray-300 text-brand-500" {{ old('allow_download', $lesson->allow_download) ? 'checked' : '' }}>
                        <span class="text-sm text-main">{{ __('teacher.allow_download') }}</span>
                    </label>
                </div>

                {{-- الحالة --}}
                <div>
                    <label class="label">{{ __('app.status') }}</label>
                    <select name="status" class="input sm:w-48">
                        <option value="draft" {{ old('status', $lesson->status) === 'draft' ? 'selected' : '' }}>{{ __('status.draft') }}</option>
                        <option value="published" {{ old('status', $lesson->status) === 'published' ? 'selected' : '' }}>{{ __('status.published') }}</option>
                        <option value="archived" {{ old('status', $lesson->status) === 'archived' ? 'selected' : '' }}>{{ __('status.archived') }}</option>
                    </select>
                </div>

                {{-- إحصائيات --}}
                <div class="flex flex-wrap gap-4 p-4 rounded-2xl bg-hover text-sm">
                    <span class="text-faint">👁 {{ number_format($lesson->view_count) }} {{ __('teacher.views') }}</span>
                    <span class="text-faint">📅 {{ __('app.created') }}: {{ $lesson->created_at->format('d/m/Y') }}</span>
                    @if($lesson->published_at)
                    <span class="text-faint">🚀 {{ __('teacher.published_at') }}: {{ $lesson->published_at->format('d/m/Y') }}</span>
                    @endif
                </div>

            </div>

            <div class="flex items-center justify-between gap-3 mt-6 pt-5 border-t border-bd">
                <form method="POST" action="{{ route('teacher.lessons.destroy', $lesson) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-100 transition">🗑️ {{ __('app.delete') }}</button>
                </form>
                <div class="flex gap-3">
                    <a href="{{ route('teacher.lessons.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
                    <button type="submit" class="btn-primary">{{ __('app.save') }}</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Lesson Contents --}}
    <div class="card animate-fade-up" style="animation-delay:.1s">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">📎</span>
                {{ __('teacher.lesson_contents') }}
            </h3>
        </div>

        @forelse($lesson->contents ?? [] as $content)
        <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd mb-2">
            <span class="text-xl">
                @switch($content->type)
                    @case('video') 🎥 @break
                    @case('audio') 🎵 @break
                    @case('image') 🖼️ @break
                    @case('document') 📄 @break
                    @default 📝
                @endswitch
            </span>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm text-main truncate">{{ $content->title ?? $content->file_name ?? __('app.content') }}</p>
                <p class="text-xs text-faint">{{ strtoupper($content->type) }}</p>
            </div>
            <form method="POST" action="{{ route('teacher.lessons.content.delete', [$lesson, $content]) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button class="text-danger-500 hover:text-danger-700 text-sm">✕</button>
            </form>
        </div>
        @empty
        <p class="text-center text-muted text-sm py-6">{{ __('teacher.no_content_yet') }}</p>
        @endforelse

        {{-- Upload Content Form --}}
        <form method="POST" action="{{ route('teacher.lessons.content.upload', $lesson) }}" enctype="multipart/form-data" class="mt-4 p-4 rounded-2xl border-2 border-dashed border-bd">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <select name="type" class="input" required>
                    <option value="text">📝 {{ __('teacher.text') }}</option>
                    <option value="video">🎥 {{ __('teacher.video') }}</option>
                    <option value="audio">🎵 {{ __('teacher.audio') }}</option>
                    <option value="image">🖼️ {{ __('teacher.image') }}</option>
                    <option value="document">📄 {{ __('teacher.document') }}</option>
                </select>
                <input type="text" name="title" class="input" placeholder="{{ __('app.title') }}">
                <input type="file" name="file" class="input">
            </div>
            <div class="mt-3">
                <input type="url" name="file_url" class="input" placeholder="{{ __('teacher.or_paste_url') }}">
            </div>
            <div class="flex justify-end mt-3">
                <button type="submit" class="btn-primary !py-2 !px-4 text-sm">📤 {{ __('teacher.upload_content') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection