@extends('layouts.app')
@section('title', __('app.schools'))
@section('page-title', __('app.schools'))
@section('page-subtitle', __('admin.schools_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $schools->total() }} {{ __('admin.schools_count') }}</p>
        <a href="{{ route('admin.schools.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('admin.add_school') }}
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
                       class="input ps-10" placeholder="{{ __('admin.search_schools') }}">
            </div>
            <select name="status" class="input sm:w-44">
                <option value="">{{ __('app.all') }}</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>{{ __('status.active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('status.inactive') }}</option>
                <option value="suspended"{{ request('status') === 'suspended'? 'selected' : '' }}>{{ __('status.suspended') }}</option>
            </select>
            <button type="submit" class="btn-outline">🔍 {{ __('app.filter') }}</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.schools.index') }}" class="btn-outline text-danger-600 px-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════ Schools Grid ══════════ --}}
    @if($schools->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 stagger">
        @foreach($schools as $school)
        <div class="card card-hover">
            <div class="flex items-start gap-3 mb-4">
                <img src="{{ $school->logo_url }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-bd flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-main truncate">{{ $school->name }}</p>
                    <p class="text-xs text-muted mt-0.5">{{ $school->city ?? '—' }}, {{ $school->country ?? '—' }}</p>
                    <span class="badge-{{ $school->status === 'active' ? 'green' : ($school->status === 'suspended' ? 'red' : 'gray') }} mt-1.5">
                        {{ __('status.'.$school->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="text-center p-2.5 rounded-xl bg-brand-50">
                    <p class="font-black text-brand-600">{{ $school->students_count }}</p>
                    <p class="text-[10px] text-brand-600">{{ __('admin.students_short') }}</p>
                </div>
                <div class="text-center p-2.5 rounded-xl bg-info-50">
                    <p class="font-black text-info-600">{{ $school->teachers_count }}</p>
                    <p class="text-[10px] text-info-600">{{ __('admin.teachers_short') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="badge-brand">{{ __('admin.'.$school->subscription_plan) }}</span>
                @if($school->subscription_expires_at)
                <span class="text-xs text-faint">
                    {{ __('admin.expires') }}: {{ $school->subscription_expires_at->format('d/m/Y') }}
                </span>
                @endif
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.schools.edit', $school) }}" class="flex-1 btn-outline text-xs text-center justify-center">
                    ✏️ {{ __('app.edit') }}
                </a>
                <form method="POST" action="{{ route('admin.schools.destroy', $school) }}"
                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button class="btn-danger text-xs">🗑️</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-center">{{ $schools->withQueryString()->links() }}</div>
    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">🏫</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('admin.no_schools_yet') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('admin.no_schools_hint') }}</p>
        <a href="{{ route('admin.schools.create') }}" class="btn-primary mt-5 inline-flex">
            {{ __('admin.add_first_school') }}
        </a>
    </div>
    @endif
</div>
@endsection