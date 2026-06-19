@extends('layouts.app')
@section('title', __('student.my_assignments'))
@section('page-title', __('student.my_assignments'))
@section('page-subtitle', __('student.my_assignments_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="input sm:w-48">
                <option value="">{{ __('app.all') }}</option>
                <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>{{ __('student.pending') }}</option>
                <option value="submitted"{{ request('status')==='submitted'? 'selected':'' }}>{{ __('student.submitted_label') }}</option>
                <option value="graded"   {{ request('status')==='graded'   ? 'selected':'' }}>{{ __('student.graded_label') }}</option>
                <option value="overdue"  {{ request('status')==='overdue'  ? 'selected':'' }}>{{ __('student.overdue') }}</option>
            </select>
            <button type="submit" class="btn-outline">🔍 {{ __('app.filter') }}</button>
            @if(request('status'))
            <a href="{{ route('student.assignments.index') }}" class="btn-outline text-danger-600 px-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>
    </div>

    {{-- ══════════ Assignments ══════════ --}}
    @forelse($assignments as $assignment)
    @php
        $subStatus = $submissionsMap[$assignment->id] ?? null;
        $overdue   = $assignment->isOverdue() && !$subStatus;
    @endphp

    <div class="card card-hover animate-fade-up" style="animation-delay:{{ .04 + $loop->index * .03 }}s
                {{ $overdue ? 'border-danger-500/30' : '' }}">
        <div class="flex items-start gap-4">

            {{-- Status Icon --}}
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        {{ $subStatus === 'graded'   ? 'bg-success-50 text-success-600'
                          : ($subStatus === 'submitted' ? 'bg-info-50 text-info-600'
                          : ($overdue ? 'bg-danger-50 text-danger-600' : 'bg-warning-50 text-warning-600')) }}">
                {{ $subStatus === 'graded' ? '✅' : ($subStatus === 'submitted' ? '📤' : ($overdue ? '⌛' : '📋')) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    @if($subStatus === 'graded')
                    <span class="badge-green">{{ __('student.graded_label') }}</span>
                    @elseif($subStatus === 'submitted')
                    <span class="badge-blue">{{ __('student.submitted_label') }}</span>
                    @elseif($overdue)
                    <span class="badge-red">{{ __('student.overdue') }}</span>
                    @else
                    <span class="badge-yellow">{{ __('student.pending') }}</span>
                    @endif
                    <span class="text-xs text-muted">{{ $assignment->subject->name }}</span>
                </div>

                <p class="font-bold text-main text-base">{{ $assignment->title }}</p>

                @if($assignment->description)
                <p class="text-muted text-sm mt-1 line-clamp-2">{{ $assignment->description }}</p>
                @endif

                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-faint">
                    <span class="flex items-center gap-1">
                        🎯 {{ $assignment->total_marks }} {{ __('teacher.marks') }}
                    </span>
                    <span class="flex items-center gap-1 {{ $overdue ? 'text-danger-600 font-bold' : '' }}">
                        📅 {{ __('teacher.due') }}: {{ $assignment->due_date->format('d/m/Y H:i') }}
                        ({{ $assignment->due_date->diffForHumans() }})
                    </span>
                </div>

                {{-- Grade (if graded) --}}
                @if($subStatus === 'graded')
                @php $sub = $assignment->submissions()->where('student_id', auth()->id())->first(); @endphp
                @if($sub && $sub->marks_obtained !== null)
                <div class="mt-3 p-3 rounded-2xl bg-success-50 border border-success-500/25 flex items-center gap-3">
                    <span class="text-2xl">🏅</span>
                    <div>
                        <p class="font-black text-success-600">
                            {{ $sub->marks_obtained }}/{{ $assignment->total_marks }}
                            ({{ round($sub->marks_obtained / $assignment->total_marks * 100) }}%)
                        </p>
                        @if($sub->teacher_feedback)
                        <p class="text-xs text-main mt-0.5">{{ $sub->teacher_feedback }}</p>
                        @endif
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

        {{-- ══════ Submission Form ══════ --}}
        @if(!$subStatus && (!$overdue || $assignment->allow_late_submission))
        <div class="mt-4 pt-4 border-t border-bd"
             x-data="{ expanded: false }">
            <button type="button" @click="expanded = !expanded"
                    class="flex items-center gap-2 text-sm font-bold text-brand-500 hover:text-brand-700 transition">
                <svg :class="expanded ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
                {{ __('student.submit_assignment') }}
                @if($overdue && $assignment->late_penalty_percent)
                <span class="badge-red text-[10px]">
                    -{{ $assignment->late_penalty_percent }}% {{ __('teacher.late_label') }}
                </span>
                @endif
            </button>

            <div x-show="expanded" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-4 animate-fade">
                <form method="POST" action="{{ route('student.assignments.submit', $assignment) }}"
                      enctype="multipart/form-data" class="space-y-3"
                      x-data="{ loading: false, fname: '' }" @submit="loading = true">
                    @csrf

                    @if(in_array($assignment->submission_type, ['text','both']))
                    <div>
                        <label class="label">📝 {{ __('student.text_answer') }}</label>
                        <textarea name="text_answer" rows="4" class="input resize-none"
                                  placeholder="{{ __('student.text_answer_placeholder') }}"></textarea>
                    </div>
                    @endif

                    @if(in_array($assignment->submission_type, ['file','both']))
                    <div>
                        <label class="label">📎 {{ __('student.file_upload') }}</label>
                        <div class="relative border-2 border-dashed border-bd rounded-2xl p-5 text-center hover:border-brand-400 transition cursor-pointer">
                            <input type="file" name="file" class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="fname = $event.target.files[0]?.name ?? ''"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip">
                            <div x-show="!fname">
                                <span class="text-3xl">📎</span>
                                <p class="text-muted text-sm mt-1">{{ __('student.file_upload_hint') }}</p>
                                <p class="text-faint text-xs">{{ __('teacher.max_size') }} {{ $assignment->max_file_size_mb ?? 10 }}MB</p>
                            </div>
                            <div x-show="fname" x-cloak>
                                <span class="text-3xl">✅</span>
                                <p class="text-success-600 font-bold text-sm mt-1" x-text="fname"></p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit" :disabled="loading" class="btn-primary">
                            <span x-show="!loading" class="flex items-center gap-2">
                                📤 {{ __('student.submit_assignment') }}
                            </span>
                            <span x-show="loading" x-cloak class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                {{ __('student.submitting') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
    @empty
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📋</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('student.no_assignments') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('student.no_assignments_hint') }}</p>
    </div>
    @endforelse

    @if($assignments->hasPages())
    <div class="flex justify-center animate-fade-up">
        {{ $assignments->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection