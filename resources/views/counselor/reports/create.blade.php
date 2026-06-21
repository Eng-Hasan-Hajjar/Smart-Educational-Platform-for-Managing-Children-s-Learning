@extends('layouts.app')
@section('title', __('counselor.create_report_title'))
@section('page-title', __('counselor.create_report_title'))
@section('page-subtitle', __('counselor.create_report_subtitle'))

@section('content')
<div class="max-w-3xl mx-auto animate-fade-up" x-data="{ studentId: '{{ old('student_id', $selectedStudent?->id) }}' }">
    <form method="GET" class="mb-5" x-show="false"></form>

    {{-- ══════════ Student Selector (triggers reload via GET to fetch snapshot) ══════════ --}}
    <div class="card mb-6">
        <label class="label">{{ __('counselor.select_student') }} *</label>
        <select onchange="window.location = '{{ route('counselor.reports.create') }}?student_id=' + this.value"
                class="input">
            <option value="">{{ __('app.select_option') }}</option>
            @foreach($students as $s)
            <option value="{{ $s->id }}" {{ ($selectedStudent?->id ?? old('student_id')) == $s->id ? 'selected' : '' }}>
                {{ $s->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- ══════════ Performance Snapshot ══════════ --}}
    @if($selectedStudent)
    <div class="card mb-6 animate-scale-in" style="background: linear-gradient(135deg, var(--brand-50), var(--info-50))">
        <div class="flex items-center gap-3 mb-4">
            <img src="{{ $selectedStudent->avatar_url }}" class="w-12 h-12 rounded-full object-cover" alt="">
            <div>
                <p class="font-bold text-main">{{ $selectedStudent->name }}</p>
                <p class="text-xs text-muted">{{ __('counselor.subject_snapshot') }}</p>
            </div>
        </div>
        @if(count($subjectsData))
        <div class="space-y-3">
            @foreach($subjectsData as $subj)
            @php $color = $subj['average_score'] >= 80 ? 'success' : ($subj['average_score'] >= 60 ? 'warning' : 'danger'); @endphp
            <div class="flex items-center gap-3">
                <p class="text-sm text-muted w-28 truncate flex-shrink-0">{{ $subj['subject'] }}</p>
                <div class="flex-1 progress-track">
                    <div class="progress-fill !bg-none" style="width: {{ $subj['average_score'] }}%; background: var(--{{ $color }}-500)"></div>
                </div>
                <span class="text-xs font-bold text-{{ $color }}-600 w-10 flex-shrink-0">{{ round($subj['average_score']) }}%</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted text-sm">{{ __('app.no_data') }}</p>
        @endif
    </div>
    @else
    <div class="card mb-6 text-center py-8 animate-fade">
        <span class="text-4xl animate-float inline-block">👨‍🎓</span>
        <p class="text-muted text-sm mt-2">{{ __('counselor.select_student_first') }}</p>
    </div>
    @endif

    {{-- ══════════ Report Form ══════════ --}}
    <form method="POST" action="{{ route('counselor.reports.store') }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf
        <input type="hidden" name="student_id" value="{{ $selectedStudent?->id }}">

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">📝</span>
                <h3 class="font-bold text-main">{{ __('counselor.create_report_title') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('counselor.select_semester') }} *</label>
                    <select name="semester_id" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ old('semester_id') == $sem->id ? 'selected' : '' }}>
                            {{ $sem->name }} — {{ $sem->academicYear->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('counselor.report_type') }} *</label>
                    <select name="type" required class="input">
                        <option value="periodic"   {{ old('type') === 'periodic'   ? 'selected' : '' }}>{{ __('counselor.type_periodic') }}</option>
                        <option value="final"      {{ old('type') === 'final'      ? 'selected' : '' }}>{{ __('counselor.type_final') }}</option>
                        <option value="behavioral" {{ old('type') === 'behavioral' ? 'selected' : '' }}>{{ __('counselor.type_behavioral') }}</option>
                        <option value="custom"     {{ old('type') === 'custom'     ? 'selected' : '' }}>{{ __('counselor.type_custom') }}</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="label">{{ __('counselor.counselor_notes_field') }} *</label>
                <textarea name="counselor_notes" rows="5" required class="input resize-none"
                          placeholder="{{ __('counselor.counselor_notes_placeholder') }}">{{ old('counselor_notes') }}</textarea>
            </div>

            <div>
                <label class="label">{{ __('counselor.recommendations_field') }}</label>
                <textarea name="recommendations" rows="4" class="input resize-none"
                          placeholder="{{ __('counselor.recommendations_placeholder') }}">{{ old('recommendations') }}</textarea>
            </div>

            <label class="flex items-center gap-2.5 cursor-pointer select-none pt-2 border-t border-bd">
                <input type="checkbox" name="send_to_parent" value="1" class="w-4 h-4 rounded-lg accent-brand-500">
                <span class="text-sm font-medium text-main">{{ __('counselor.send_to_parent_now') }}</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('counselor.reports.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 {{ __('counselor.save_report') }}</span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ __('app.loading') }}
                </span>
            </button>
        </div>
    </form>
</div>
@endsection