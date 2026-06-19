@extends('layouts.app')
@section('title', __('teacher.my_students'))
@section('page-title', __('teacher.my_students'))
@section('page-subtitle', __('teacher.my_students_subtitle'))

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
                       class="input ps-10" placeholder="{{ __('teacher.search_students') }}">
            </div>
            <select name="classroom_id" class="input">
                <option value="">{{ __('app.classrooms') }}</option>
                @foreach($classrooms as $c)
                <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
                @endforeach
            </select>
            <select name="status" class="input">
                <option value="">{{ __('teacher.all_statuses') }}</option>
                @foreach(['excellent'=>__('status.excellent'),'good'=>__('status.good'),'average'=>__('status.average'),'needs_support'=>__('status.needs_support'),'at_risk'=>__('status.at_risk')] as $v=>$l)
                <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    🔍 {{ __('app.search') }}
                </button>
                @if(request()->hasAny(['search','classroom_id','status']))
                <a href="{{ route('teacher.students.index') }}" class="btn-outline px-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ══════════ Students Grid ══════════ --}}
    @if($students->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 stagger">
        @foreach($students as $student)
        <a href="{{ route('teacher.students.show', $student) }}"
           class="card card-hover group block">

            <div class="flex items-center gap-3 mb-4">
                <div class="avatar-ring flex-shrink-0">
                    <img src="{{ $student->avatar_url }}" class="w-12 h-12 object-cover" alt="">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-main group-hover:text-brand-500 transition truncate">
                        {{ $student->name }}
                    </p>
                    <p class="text-xs text-muted">
                        {{ $student->studentProfile?->academicLevel?->name ?? '—' }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }}">
                            {{ $student->studentProfile?->status_label ?? '—' }}
                        </span>
                        @foreach($student->classrooms->take(1) as $c)
                        <span class="badge-brand">{{ $c->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Gamification --}}
            @if($student->gamification)
            <div class="bg-surface2 rounded-2xl p-3 mb-3">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-muted font-semibold">
                        🏆 {{ $student->gamification->level_title }}
                        ({{ __('student.level') }} {{ $student->gamification->level }})
                    </span>
                    <span class="font-black text-brand-500">
                        {{ number_format($student->gamification->total_points) }} {{ __('student.pts') }}
                    </span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: {{ $student->gamification->level_progress }}%"></div>
                </div>
            </div>
            @endif

            {{-- View Profile Arrow --}}
            <div class="flex items-center justify-end gap-1 text-xs text-brand-500 font-bold group-hover:gap-2 transition-all">
                {{ __('teacher.view_student_profile') }}
                <svg class="w-3.5 h-3.5 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>
        @endforeach
    </div>

    <div class="flex justify-center">{{ $students->withQueryString()->links() }}</div>

    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">👨‍🎓</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.no_students_found') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_students_found_hint') }}</p>
        @if(request()->hasAny(['search','classroom_id','status']))
        <a href="{{ route('teacher.students.index') }}" class="btn-outline mt-4 inline-flex">
            {{ __('teacher.clear_filters') }}
        </a>
        @endif
    </div>
    @endif
</div>
@endsection