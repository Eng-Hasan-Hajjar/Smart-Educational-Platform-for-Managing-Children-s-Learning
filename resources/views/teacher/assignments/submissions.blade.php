@extends('layouts.app')
@section('title','تسليمات الواجب')
@section('page-title','📋 تسليمات — {{ $assignment->title }}')
@section('content')
<div class="space-y-4">
    <div class="card bg-slate-50 border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">{{ $assignment->title }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $assignment->subject->name }} · {{ $assignment->classroom->name }} · موعد التسليم: {{ $assignment->due_date->format('d M Y') }}</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-primary">{{ $submissions->where('status','submitted')->count() + $submissions->where('status','graded')->count() }}</p>
                <p class="text-xs text-slate-400">/ {{ $assignment->classroom->students->count() }} طالب</p>
            </div>
        </div>
    </div>

    @forelse($submissions as $sub)
    <div class="card">
        <div class="flex items-start gap-4">
            <img src="{{ $sub->student->avatar_url }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="font-bold text-slate-800">{{ $sub->student->name }}</p>
                    <span class="badge-{{ $sub->status==='graded'?'green':'blue' }}">{{ $sub->status==='graded'?'تم التصحيح':'بانتظار التصحيح' }}</span>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    سُلِّم {{ $sub->created_at->diffForHumans() }}
                    @if($sub->is_late)<span class="text-red-500 font-bold"> — متأخر</span>@endif
                </p>

                @if($sub->text_answer)
                <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-bold text-slate-500 mb-1">الإجابة النصية:</p>
                    <p class="text-sm text-slate-700">{{ Str::limit($sub->text_answer, 200) }}</p>
                </div>
                @endif

                @if($sub->file_path)
                <a href="{{ $sub->file_url }}" target="_blank" class="inline-flex items-center gap-2 mt-2 text-xs text-secondary hover:underline">
                    📎 {{ $sub->file_name }} ({{ $sub->file_size_human }})
                </a>
                @endif

                @if($sub->status === 'graded')
                <div class="mt-3 p-3 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-sm font-bold text-green-700">الدرجة: {{ $sub->marks_obtained }}/{{ $assignment->total_marks }} ({{ $sub->percentage }}%)</p>
                    @if($sub->teacher_feedback)<p class="text-xs text-slate-600 mt-1">{{ $sub->teacher_feedback }}</p>@endif
                </div>
                @else
                <form method="POST" action="{{ route('teacher.assignments.grade',[$assignment,$sub]) }}" class="mt-3 flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="label">الدرجة (من {{ $assignment->total_marks }})</label>
                        <input type="number" name="marks_obtained" class="input" min="0" max="{{ $assignment->total_marks }}" required>
                    </div>
                    <div class="flex-1">
                        <label class="label">ملاحظات المعلم</label>
                        <input type="text" name="teacher_feedback" class="input" placeholder="اختياري">
                    </div>
                    <button type="submit" class="btn-primary text-sm py-3">✅ تصحيح</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-12">
        <span class="text-5xl">📭</span>
        <p class="text-slate-400 mt-3">لم يُسلَّم أي واجب بعد</p>
    </div>
    @endforelse
</div>
@endsection