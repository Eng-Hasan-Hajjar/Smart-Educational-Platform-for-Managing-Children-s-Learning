@extends('layouts.app')
@section('title', __('app.attendance'))
@section('page-title', __('app.attendance'))
@section('page-subtitle', __('teacher.attendance_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="label">{{ __('app.classrooms') }}</label>
                <select name="classroom_id" class="input">
                    <option value="">{{ __('app.select_option') }}</option>
                    @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ request('classroom_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} — {{ $c->academicLevel->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">{{ __('teacher.attendance_date') }}</label>
                <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}" class="input">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    {{ __('teacher.load_students') }}
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════ Quick Date Nav ══════════ --}}
    @if(request('classroom_id'))
    <div class="flex items-center justify-center gap-2 animate-fade-up" style="animation-delay:.04s">
        @php
            $currentDate = request('date', today()->toDateString());
            $prevDate = \Carbon\Carbon::parse($currentDate)->subDay()->toDateString();
            $nextDate = \Carbon\Carbon::parse($currentDate)->addDay()->toDateString();
        @endphp
        <a href="?classroom_id={{ request('classroom_id') }}&date={{ $prevDate }}"
           class="btn-outline !py-2 !px-3">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="card !p-3 text-center min-w-[160px]">
            <p class="font-black text-main text-sm">
                {{ \Carbon\Carbon::parse($currentDate)->format('d/m/Y') }}
            </p>
            <p class="text-xs text-muted">
                {{ __('app.' . strtolower(\Carbon\Carbon::parse($currentDate)->format('l'))) }}
            </p>
        </div>

        <a href="?classroom_id={{ request('classroom_id') }}&date={{ $nextDate }}"
           class="btn-outline !py-2 !px-3">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endif

    {{-- ══════════ Attendance Form ══════════ --}}
    @if(isset($students) && $students->count() && $selectedClassroom)

    {{-- Stats Banner --}}
    @php
        $presentCount = collect($existingAttendance)->filter(fn($s) => $s === 'present')->count();
        $absentCount  = collect($existingAttendance)->filter(fn($s) => $s === 'absent')->count();
        $lateCount    = collect($existingAttendance)->filter(fn($s) => $s === 'late')->count();
        $excusedCount = collect($existingAttendance)->filter(fn($s) => $s === 'excused')->count();
        $savedCount   = count($existingAttendance);
    @endphp
    @if($savedCount > 0)
    <div class="card !p-4 animate-fade-up" style="animation-delay:.06s">
        <p class="text-xs font-bold text-muted uppercase tracking-widest mb-3">{{ __('teacher.attendance_summary') }}</p>
        <div class="grid grid-cols-4 gap-3">
            @foreach([
                ['label'=>__('status.present'), 'value'=>$presentCount, 'ring'=>'success', 'icon'=>'✅'],
                ['label'=>__('status.absent'),  'value'=>$absentCount,  'ring'=>'danger',  'icon'=>'❌'],
                ['label'=>__('status.late'),    'value'=>$lateCount,    'ring'=>'warning', 'icon'=>'⏰'],
                ['label'=>__('status.excused'), 'value'=>$excusedCount, 'ring'=>'info',    'icon'=>'📋'],
            ] as $s)
            <div class="text-center p-3 rounded-2xl bg-{{ $s['ring'] }}-50">
                <p class="text-xl mb-1">{{ $s['icon'] }}</p>
                <p class="text-2xl font-black text-{{ $s['ring'] }}-600">{{ $s['value'] }}</p>
                <p class="text-{{ $s['ring'] }}-600 text-xs font-medium">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('teacher.attendance.store') }}"
          x-data="{
              statuses: @js(
                  $students->pluck('id')->mapWithKeys(fn($id) =>
                      [$id => $existingAttendance[$id] ?? 'present']
                  )->toArray()
              ),
              markAll(status) {
                  Object.keys(this.statuses).forEach(id => this.statuses[id] = status);
              },
              getCount(status) {
                  return Object.values(this.statuses).filter(s => s === status).length;
              }
          }"
          class="space-y-4 animate-fade-up" style="animation-delay:.08s">
        @csrf
        <input type="hidden" name="classroom_id" value="{{ $selectedClassroom->id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        {{-- Mark All Row --}}
        <div class="card !p-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <p class="font-bold text-main">{{ $selectedClassroom->name }}</p>
                    <p class="text-xs text-muted mt-0.5">{{ $students->count() }} {{ __('teacher.students_total') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-muted font-medium">{{ __('teacher.mark_all') }}:</span>
                    @foreach([
                        ['status'=>'present', 'label'=>__('status.present'), 'icon'=>'✅', 'style'=>'bg-success-50 text-success-600 hover:bg-success-500 hover:text-white'],
                        ['status'=>'absent',  'label'=>__('status.absent'),  'icon'=>'❌', 'style'=>'bg-danger-50 text-danger-600 hover:bg-danger-500 hover:text-white'],
                        ['status'=>'late',    'label'=>__('status.late'),    'icon'=>'⏰', 'style'=>'bg-warning-50 text-warning-600 hover:bg-warning-500 hover:text-white'],
                        ['status'=>'excused', 'label'=>__('status.excused'), 'icon'=>'📋', 'style'=>'bg-info-50 text-info-600 hover:bg-info-500 hover:text-white'],
                    ] as $btn)
                    <button type="button" @click="markAll('{{ $btn['status'] }}')"
                            class="text-xs font-bold px-3 py-1.5 rounded-xl transition {{ $btn['style'] }}">
                        {{ $btn['icon'] }} {{ $btn['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Live Counter --}}
            <div class="mt-3 pt-3 border-t border-bd grid grid-cols-4 gap-2 text-center text-xs">
                @foreach(['present'=>['success','✅'], 'absent'=>['danger','❌'], 'late'=>['warning','⏰'], 'excused'=>['info','📋']] as $st=>[$color,$icon])
                <div class="bg-{{ $color }}-50 rounded-xl py-2">
                    <span>{{ $icon }}</span>
                    <span class="font-black text-{{ $color }}-600 ms-1" x-text="getCount('{{ $st }}')"></span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Students List --}}
        <div class="space-y-2">
            @foreach($students as $i => $student)
            <div class="card !p-3 animate-slide" style="animation-delay:{{ .02 * $i }}s">
                <div class="flex items-center gap-3">

                    {{-- Number --}}
                    <span class="w-7 h-7 rounded-lg bg-surface2 text-muted text-xs font-black flex items-center justify-center flex-shrink-0">
                        {{ $i + 1 }}
                    </span>

                    {{-- Avatar + Name --}}
                    <img src="{{ $student->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-main truncate">{{ $student->name }}</p>
                        <p class="text-xs text-muted">{{ $student->studentProfile?->student_number ?? '' }}</p>
                    </div>

                    {{-- Hidden inputs --}}
                    <input type="hidden" :name="'student_ids[]'" value="{{ $student->id }}">
                    <input type="hidden" :name="'status[{{ $student->id }}]'" :value="statuses[{{ $student->id }}]">

                    {{-- Status Buttons --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        @foreach([
                            ['status'=>'present', 'icon'=>'✅', 'label'=>__('status.present'), 'active'=>'bg-success-500 text-white border-success-500', 'idle'=>'border-bd text-muted hover:border-success-400 hover:bg-success-50'],
                            ['status'=>'absent',  'icon'=>'❌', 'label'=>__('status.absent'),  'active'=>'bg-danger-500 text-white border-danger-500',  'idle'=>'border-bd text-muted hover:border-danger-400 hover:bg-danger-50'],
                            ['status'=>'late',    'icon'=>'⏰', 'label'=>__('status.late'),    'active'=>'bg-warning-500 text-white border-warning-500', 'idle'=>'border-bd text-muted hover:border-warning-400 hover:bg-warning-50'],
                            ['status'=>'excused', 'icon'=>'📋', 'label'=>__('status.excused'), 'active'=>'bg-info-500 text-white border-info-500',       'idle'=>'border-bd text-muted hover:border-info-400 hover:bg-info-50'],
                        ] as $btn)
                        <button type="button"
                                @click="statuses[{{ $student->id }}] = '{{ $btn['status'] }}'"
                                :class="statuses[{{ $student->id }}] === '{{ $btn['status'] }}'
                                    ? '{{ $btn['active'] }}'
                                    : '{{ $btn['idle'] }}'"
                                class="w-9 h-9 sm:w-auto sm:px-3 sm:h-9 rounded-xl border text-xs font-bold transition-all flex items-center justify-center gap-1"
                                :title="'{{ $btn['label'] }}'">
                            <span>{{ $btn['icon'] }}</span>
                            <span class="hidden sm:inline">{{ $btn['label'] }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Submit --}}
        <div class="flex justify-end animate-fade-up" style="animation-delay:.3s">
            <button type="submit" class="btn-primary !py-3 !px-8 text-base">
                💾 {{ __('teacher.save_attendance') }}
            </button>
        </div>
    </form>

    @elseif(request('classroom_id') && (!isset($students) || $students->count() === 0))
    <div class="card text-center py-12 animate-fade">
        <span class="text-5xl animate-float inline-block">👥</span>
        <p class="font-bold text-main mt-3">{{ __('teacher.no_students_in_classroom') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_students_hint') }}</p>
    </div>

    @else
    {{-- Prompt to select classroom --}}
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📅</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.select_classroom_prompt') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.select_classroom_hint') }}</p>
    </div>
    @endif
</div>
@endsection