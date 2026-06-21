@extends('layouts.app')
@section('title', __('app.subjects'))
@section('page-title', __('app.subjects'))
@section('page-subtitle', __('schooladmin.subjects_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $subjects->total() }} {{ __('schooladmin.subjects_count') }}</p>
        <a href="{{ route('school-admin.subjects.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('schooladmin.add_subject') }}
        </a>
    </div>

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input ps-10" placeholder="{{ __('schooladmin.search_subjects') }}">
            </div>
            <select name="level_id" class="input sm:w-52">
                <option value="">{{ __('schooladmin.all_levels') }}</option>
                @foreach($levels as $level)
                <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                    {{ $level->name }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline">🔍 {{ __('app.filter') }}</button>
            @if(request()->hasAny(['search','level_id']))
            <a href="{{ route('school-admin.subjects.index') }}" class="btn-outline text-danger-600 px-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════ Subjects Grid ══════════ --}}
    @if($subjects->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 stagger">
        @foreach($subjects as $subject)
        <div class="card card-hover">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0"
                     style="background: {{ $subject->color }}1A; color: {{ $subject->color }}">
                    {{ $subject->icon ?? '📖' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-main truncate">{{ $subject->name }}</p>
                    @if($subject->name_en)
                    <p class="text-xs text-muted mt-0.5">{{ $subject->name_en }}</p>
                    @endif
                    <span class="badge-{{ $subject->is_active ? 'green' : 'gray' }} mt-1.5">
                        {{ $subject->is_active ? __('status.active') : __('status.inactive') }}
                    </span>
                </div>
            </div>

            @if($subject->description)
            <p class="text-muted text-sm mb-4 line-clamp-2">{{ $subject->description }}</p>
            @endif

            {{-- Academic Level (single, belongsTo) --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="badge-brand text-xs">🎓 {{ $subject->academicLevel->name }}</span>
                @if($subject->code)
                <span class="badge-gray text-xs">{{ $subject->code }}</span>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="text-center p-2.5 rounded-xl bg-info-50">
                    <p class="font-black text-info-600">{{ $subject->units_count }}</p>
                    <p class="text-[10px] text-info-600">{{ __('schooladmin.units_count') }}</p>
                </div>
                <div class="text-center p-2.5 rounded-xl bg-success-50">
                    <p class="font-black text-success-600">{{ $subject->weekly_hours }}</p>
                    <p class="text-[10px] text-success-600">{{ __('schooladmin.weekly_hours_short') }}</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('school-admin.subjects.edit', $subject) }}" class="flex-1 btn-outline text-xs text-center justify-center">
                    ✏️ {{ __('app.edit') }}
                </a>
                <form method="POST" action="{{ route('school-admin.subjects.destroy', $subject) }}"
                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button class="btn-danger text-xs">🗑️</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-center">{{ $subjects->withQueryString()->links() }}</div>
    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📚</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('schooladmin.no_subjects_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('schooladmin.no_subjects_hint') }}</p>
        <a href="{{ route('school-admin.subjects.create') }}" class="btn-primary mt-5 inline-flex">
            {{ __('schooladmin.add_first_subject') }}
        </a>
    </div>
    @endif
</div>
@endsection