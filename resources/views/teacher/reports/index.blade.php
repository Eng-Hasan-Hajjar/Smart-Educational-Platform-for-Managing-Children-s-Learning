@extends('layouts.app')
@section('title', __('app.reports'))
@section('page-title', __('app.reports'))
@section('page-subtitle', __('teacher.reports_subtitle'))

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
            <div class="flex items-end sm:col-span-2">
                <button type="submit" class="btn-primary justify-center">
                    📊 {{ __('teacher.generate_report') }}
                </button>
            </div>
        </form>
    </div>

    {{-- ══════════ Report Content ══════════ --}}
    @if(count($report) > 0 && $selectedClassroom)

    {{-- Class Summary --}}
    @php
        $avgAttendance = round(collect($report)->avg('attendance_rate'), 1);
        $avgScore      = round(collect($report)->avg('avg_score'), 1);
        $atRiskCount   = collect($report)->filter(fn($r) => $r['attendance_rate'] < 75 || $r['avg_score'] < 50)->count();
        $excellentCount= collect($report)->filter(fn($r) => $r['avg_score'] >= 90)->count();
    @endphp

    <div class="card animate-fade-up" style="animation-delay:.04s">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">🏛️</span>
                {{ $selectedClassroom->name }} — {{ $selectedClassroom->academicLevel->name }}
            </h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 stagger">
            @foreach([
                ['label'=>__('teacher.total_students'),   'value'=>count($report),  'icon'=>'👥','ring'=>'brand'],
                ['label'=>__('teacher.avg_attendance'),   'value'=>$avgAttendance.'%','icon'=>'📅','ring'=>'info'],
                ['label'=>__('teacher.avg_score_label'),  'value'=>$avgScore.'%',    'icon'=>'📊','ring'=>'success'],
                ['label'=>__('teacher.needs_attention'),  'value'=>$atRiskCount,     'icon'=>'⚠️','ring'=>'warning','pulse'=>$atRiskCount>0],
            ] as $s)
            <div class="text-center p-4 rounded-2xl bg-{{ $s['ring'] }}-50
                        {{ ($s['pulse'] ?? false) ? 'animate-pulse-glow' : '' }}">
                <p class="text-2xl mb-1">{{ $s['icon'] }}</p>
                <p class="text-2xl font-black text-{{ $s['ring'] }}-600">{{ $s['value'] }}</p>
                <p class="text-{{ $s['ring'] }}-600 text-xs font-medium mt-0.5">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Report Table --}}
    <div class="card overflow-hidden !p-0 animate-fade-up" style="animation-delay:.06s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface2 border-b border-bd">
                    <tr>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">#</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('counselor.student') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('teacher.attendance_rate') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('teacher.avg_score_label') }}</th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold">{{ __('teacher.lessons_done_label') }}</th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold">{{ __('teacher.assignments_label') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('teacher.status_label') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bd">
                    @foreach($report as $i => $row)
                    @php
                        $attColor   = $row['attendance_rate'] >= 90 ? 'success' : ($row['attendance_rate'] >= 75 ? 'warning' : 'danger');
                        $scoreColor = $row['avg_score'] >= 80 ? 'success' : ($row['avg_score'] >= 60 ? 'warning' : 'danger');
                        $isAtRisk   = $row['attendance_rate'] < 75 || $row['avg_score'] < 50;
                    @endphp
                    <tr class="hover:bg-hover transition {{ $isAtRisk ? 'bg-danger-50/40' : '' }} animate-slide" style="animation-delay:{{ .02 * $i }}s">
                        <td class="py-3 px-4 text-faint text-xs">{{ $i + 1 }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $row['student']->avatar_url }}"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                <div class="min-w-0">
                                    <p class="font-bold text-main truncate">{{ $row['student']->name }}</p>
                                    <p class="text-xs text-muted">{{ $row['student']->studentProfile?->status_label ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 progress-track">
                                    <div class="progress-fill !bg-none"
                                         style="width: {{ $row['attendance_rate'] }}%;
                                                background: var(--{{ $attColor }}-500)">
                                    </div>
                                </div>
                                <span class="text-xs font-black text-{{ $attColor }}-600">
                                    {{ $row['attendance_rate'] }}%
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 progress-track">
                                    <div class="progress-fill !bg-none"
                                         style="width: {{ $row['avg_score'] }}%;
                                                background: var(--{{ $scoreColor }}-500)">
                                    </div>
                                </div>
                                <span class="text-xs font-black text-{{ $scoreColor }}-600">
                                    {{ $row['avg_score'] }}%
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-main">{{ $row['lessons_done'] }}</td>
                        <td class="py-3 px-4 text-center font-bold text-main">{{ $row['assignments_done'] }}</td>
                        <td class="py-3 px-4">
                            @if($isAtRisk)
                            <span class="badge-red">⚠️ {{ __('teacher.needs_attention_label') }}</span>
                            @elseif($row['avg_score'] >= 90)
                            <span class="badge-green">⭐ {{ __('status.excellent') }}</span>
                            @else
                            <span class="badge-{{ $row['student']->studentProfile?->status_color ?? 'gray' }}">
                                {{ $row['student']->studentProfile?->status_label ?? '—' }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('teacher.reports.student', $row['student']) }}"
                               class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                                {{ __('app.view_details') }}
                            </a>
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
        <p class="font-bold text-main mt-3">{{ __('teacher.no_report_data') }}</p>
        <p class="text-muted text-sm mt-1">{{ __('teacher.no_report_hint') }}</p>
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