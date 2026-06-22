@extends('layouts.app')
@section('title', __('app.units'))
@section('page-title', __('app.units'))

@section('content')
<div class="space-y-5">

    {{-- Topbar --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $units->total() }} {{ __('teacher.units_count') }}</p>
        <a href="{{ route('teacher.units.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('teacher.new_unit') }}
        </a>
    </div>

    {{-- Filter --}}
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="subject_id" class="input sm:w-56">
                <option value="">{{ __('app.all_subjects') }}</option>
                @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline">{{ __('app.filter') }}</button>
            @if(request('subject_id'))
            <a href="{{ route('teacher.units.index') }}" class="btn-outline text-danger-600">✕</a>
            @endif
        </form>
    </div>

    {{-- Units List --}}
    @forelse($units as $unit)
    <div class="card card-hover animate-fade-up" style="animation-delay:{{ .06 + $loop->index * .04 }}s">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        {{ $unit->is_published ? 'bg-success-50 text-success-600' : 'bg-hover text-faint' }}">
                📚
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="badge-{{ $unit->is_published ? 'green' : 'yellow' }}">
                        {{ $unit->is_published ? __('status.published') : __('status.draft') }}
                    </span>
                    <span class="text-xs text-muted">{{ $unit->subject->name ?? '' }}</span>
                </div>
                <a href="{{ route('teacher.units.edit', $unit) }}" class="font-bold text-main hover:text-brand-500 transition text-base">
                    {{ $unit->name }}
                </a>
                @if($unit->description)
                <p class="text-muted text-xs mt-1 line-clamp-2">{{ $unit->description }}</p>
                @endif
                <div class="flex items-center gap-3 mt-2 text-xs text-faint">
                    <span>📖 {{ $unit->lessons_count }} {{ __('teacher.lessons_in_unit') }}</span>
                    <span>📊 {{ __('app.order') }}: {{ $unit->order }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="{{ route('teacher.units.edit', $unit) }}" class="btn-outline !py-2 !px-3 text-xs">✏️ {{ __('app.edit') }}</a>
                <form method="POST" action="{{ route('teacher.units.destroy', $unit) }}" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 transition">🗑️</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📚</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.no_units_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_units_hint') }}</p>
        <a href="{{ route('teacher.units.create') }}" class="btn-primary mt-5 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('teacher.create_first_unit') }}
        </a>
    </div>
    @endforelse

    @if($units->hasPages())
    <div class="flex justify-center">{{ $units->links() }}</div>
    @endif
</div>
@endsection