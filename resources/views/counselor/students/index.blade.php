@extends('layouts.app')
@section('title', __('app.students'))
@section('page-title', __('app.students'))
@section('page-subtitle', __('counselor.students_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input ps-10" placeholder="{{ __('counselor.search_students') }}">
            </div>
            <select name="classroom_id" class="input">
                <option value="">{{ __('counselor.all_classrooms') }}</option>
                @foreach($classrooms as $c)
                <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="status" class="input">
                <option value="">{{ __('counselor.all_statuses') }}</option>
                @foreach(['excellent'=>__('status.excellent'),'good'=>__('status.good'),'average'=>__('status.average'),'needs_support'=>__('status.needs_support'),'at_risk'=>__('status.at_risk')] as $v=>$l)
                <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center">🔍 {{ __('app.search') }}</button>
                @if(request()->hasAny(['search','classroom_id','status']))
                <a href="{{ route('counselor.students.index') }}" class="btn-outline px-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══════════ Students Table ══════════ --}}
    @if($students->count())
    <div class="card overflow-hidden !p-0 animate-fade-up" style="animation-delay:.04s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface2 border-b border-bd">
                    <tr>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('counselor.student') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('app.classrooms') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('counselor.status') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bd">
                    @foreach($students as $student)
                    <tr class="hover:bg-hover transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $student->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                                <div class="min-w-0">
                                    <p class="font-bold text-main truncate">{{ $student->name }}</p>
                                    <p class="text-xs text-muted">{{ $student->studentProfile?->academicLevel?->name ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-muted text-xs">{{ $student->classrooms->first()?->name ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }}">
                                {{ $student->studentProfile?->status_label ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('counselor.students.show', $student) }}"
                                   class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                                    {{ __('counselor.view_profile') }}
                                </a>
                                <span class="text-faint">·</span>
                                <a href="{{ route('counselor.reports.create') }}?student_id={{ $student->id }}"
                                   class="text-xs font-bold text-success-600 hover:text-success-500 transition">
                                    {{ __('counselor.write_report') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-center">{{ $students->withQueryString()->links() }}</div>
    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">👨‍🎓</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('counselor.no_students_found') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('counselor.no_students_found_hint') }}</p>
    </div>
    @endif
</div>
@endsection