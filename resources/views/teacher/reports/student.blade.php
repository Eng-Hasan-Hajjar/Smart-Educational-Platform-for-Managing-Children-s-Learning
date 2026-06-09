@extends('layouts.app')
@section('title','تقرير الطالب')
@section('page-title','📋 تقرير — {{ $student->name }}')
@section('content')
<div class="space-y-6">
    <a href="{{ route('teacher.reports.index') }}" class="btn-outline text-sm inline-block">← رجوع</a>

    <div class="card flex items-center gap-4">
        <img src="{{ $student->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover border-4 border-slate-100" alt="">
        <div>
            <h2 class="text-xl font-black text-slate-800">{{ $student->name }}</h2>
            <p class="text-slate-400 text-sm">{{ $student->email }}</p>
            <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }} mt-1">{{ $student->studentProfile?->status_label }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4">📚 تقدم الدروس</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($progress as $p)
                <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50">
                    <span class="text-lg">{{ $p->is_completed ? '✅' : '⏳' }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $p->lesson->title }}</p>
                        <p class="text-xs text-slate-400">{{ $p->lesson->unit->subject->name ?? '' }}</p>
                    </div>
                    <span class="text-xs font-bold {{ $p->is_completed ? 'text-green-600' : 'text-secondary' }}">{{ $p->progress_percentage }}%</span>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">لا يوجد تقدم بعد</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4">📝 نتائج الاختبارات</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($quizAttempts as $attempt)
                <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50">
                    <span class="text-lg">{{ $attempt->is_passed ? '✅' : '❌' }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $attempt->quiz->title }}</p>
                        <p class="text-xs text-slate-400">{{ $attempt->submitted_at?->format('d M Y') }}</p>
                    </div>
                    <span class="font-black text-sm {{ $attempt->is_passed ? 'text-green-600' : 'text-red-500' }}">{{ round($attempt->percentage) }}%</span>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">لا توجد اختبارات</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4">📋 الواجبات</h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($submissions as $sub)
                <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50">
                    <span class="text-lg">{{ $sub->marks_obtained !== null ? '✅' : '⏳' }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $sub->assignment->title }}</p>
                        <p class="text-xs text-slate-400">{{ $sub->created_at->format('d M Y') }}</p>
                    </div>
                    @if($sub->marks_obtained !== null)
                    <span class="font-black text-sm text-primary">{{ $sub->marks_obtained }}/{{ $sub->assignment->total_marks }}</span>
                    @else
                    <span class="badge-yellow">معلق</span>
                    @endif
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">لا توجد واجبات</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4">✅ الحضور والغياب (آخر 30 يوم)</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($attendances as $att)
                <div title="{{ $att->date->format('d M') }}"
                     class="w-7 h-7 rounded-lg flex items-center justify-center text-xs
                     {{ $att->status==='present'?'bg-green-100 text-green-700':($att->status==='absent'?'bg-red-100 text-red-700':($att->status==='late'?'bg-yellow-100 text-yellow-700':'bg-blue-100 text-blue-700')) }}">
                    {{ $att->status_icon }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection