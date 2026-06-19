@extends('layouts.app')
@section('title', __('teacher.attendance_report'))
@section('page-title', __('teacher.attendance_report'))
@section('page-subtitle', __('teacher.attendance_report_subtitle'))

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
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">{{ __('teacher.filter_month') }}</label>
                <input type="month" name="month"
                       value="{{ request('month', now()->format('Y-m')) }}" class="input">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full justify-center">
                    📊 {{ __('teacher.generate_report') }}
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════ Report Table ══════════ --}}
    @if(count($report) > 0)

    {{-- School-level summary --}}
    @php
        $avgRate = count($report) > 0
            ? round(collect($report)->avg('attendance_rate'), 1)
            : 0;
        $atRiskStudents = collect($report)->filter(fn($r) => $r['attendance_rate'] < 75);
    @endphp

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 stagger animate-fade-up" style="animation-delay:.04s">
        @foreach([
            ['label'=>__('teacher.total_students'),       'value'=>count($report), 'icon'=>'👥', 'ring'=>'brand'],
            ['label'=>__('teacher.avg_attendance_rate'),  'value'=>$avgRate.'%',   'icon'=>'📊', 'ring'=>'info'],
            ['label'=>__('teacher.at_risk_attendance'),   'value'=>$atRiskStudents->count(), 'icon'=>'⚠️', 'ring'=>'warning', 'pulse'=>$atRiskStudents->count()>0],
            ['label'=>__('teacher.full_attendance'),      'value'=>collect($report)->filter(fn($r)=>$r['attendance_rate']===100.0)->count(), 'icon'=>'⭐', 'ring'=>'success'],
        ] as $s)
        <div class="card card-hover">
            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3
                        bg-{{ $s['ring'] }}-50 text-{{ $s['ring'] }}-600
                        {{ ($s['pulse'] ?? false) ? 'animate-pulse-glow' : '' }}">
                {{ $s['icon'] }}
            </div>
            <p class="text-2xl font-black text-main">{{ $s['value'] }}</p>
            <p class="text-muted text-xs mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- At-Risk Alert --}}
    @if($atRiskStudents->count() > 0)
    <div class="card !p-4 border-warning-500/30 bg-warning-50 animate-fade-up flex items-center gap-4" style="animation-delay:.06s">
        <div class="w-12 h-12 rounded-2xl bg-warning-500/15 text-warning-600 flex items-center justify-center text-2xl flex-shrink-0 animate-pulse-glow">
            ⚠️
        </div>
        <div>
            <p class="font-bold text-warning-600">{{ __('teacher.attendance_warning_title') }}</p>
            <p class="text-sm text-main mt-0.5">
                {{ __('teacher.attendance_warning_body', ['count' => $atRiskStudents->count()]) }}
            </p>
        </div>
    </div>
    @endif

    {{-- Report Table --}}
    <div class="card overflow-hidden !p-0 animate-fade-up" style="animation-delay:.08s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface2 border-b border-bd">
                    <tr>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">#</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('counselor.student') }}</th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold">
                            <span class="text-success-600">✅</span> {{ __('status.present') }}
                        </th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold">
                            <span class="text-danger-600">❌</span> {{ __('status.absent') }}
                        </th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold">
                            <span class="text-warning-600">⏰</span> {{ __('status.late') }}
                        </th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold">
                            <span class="text-info-600">📋</span> {{ __('status.excused') }}
                        </th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('teacher.attendance_rate') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bd">
                    @foreach($report as $i => $row)
                    @php
                        $rate  = $row['attendance_rate'];
                        $color = $rate >= 90 ? 'success' : ($rate >= 75 ? 'warning' : 'danger');
                    @endphp
                    <tr class="hover:bg-hover transition animate-fade-up" style="animation-delay:{{ .02 * $i }}s">
                        <td class="py-3 px-4 text-faint text-xs">{{ $i + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $row['student']->avatar_url }}"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                <div class="min-w-0">
                                    <p class="font-bold text-main truncate">{{ $row['student']->name }}</p>
                                    <p class="text-xs text-muted">{{ $row['student']->studentProfile?->student_number ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-success-600">{{ $row['present'] }}</td>
                        <td class="py-3 px-4 text-center font-bold text-danger-600">{{ $row['absent'] }}</td>
                        <td class="py-3 px-4 text-center font-bold text-warning-600">{{ $row['late'] }}</td>
                        <td class="py-3 px-4 text-center font-bold text-info-600">{{ $row['excused'] }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 progress-track min-w-[60px]">
                                    <div class="progress-fill !bg-none"
                                         style="width: {{ $rate }}%;
                                                background: var(--{{ $color }}-500)">
                                    </div>
                                </div>
                                <span class="font-black text-xs text-{{ $color }}-600 w-10 flex-shrink-0">
                                    {{ $rate }}%
                                </span>
                                @if($rate < 75)
                                <span class="badge-red">{{ __('teacher.at_risk_label') }}</span>
                                @elseif($rate === 100.0)
                                <span class="badge-green">⭐</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @elseif(request('classroom_id'))
    <div class="card text-center py-12 animate-fade">
        <span class="text-5xl animate-float inline-block">📊</span>
        <p class="font-bold text-main mt-3">{{ __('teacher.no_attendance_data') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_attendance_hint') }}</p>
    </div>

    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📊</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('teacher.select_classroom_prompt') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.report_select_hint') }}</p>
    </div>
    @endif
</div>
@endsection