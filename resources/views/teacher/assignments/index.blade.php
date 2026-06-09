@extends('layouts.app')
@section('title','الواجبات')
@section('page-title','📋 إدارة الواجبات')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-slate-500 text-sm">{{ $assignments->total() }} واجب</p>
        <a href="{{ route('teacher.assignments.create') }}" class="btn-primary">➕ واجب جديد</a>
    </div>

    @forelse($assignments as $assignment)
    <div class="card">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge-{{ $assignment->status==='published'?'green':($assignment->status==='closed'?'red':'gray') }}">
                        {{ $assignment->status==='published'?'منشور':($assignment->status==='closed'?'مغلق':'مسودة') }}
                    </span>
                    @if($assignment->isOverdue() && $assignment->status==='published')
                    <span class="badge-red">انتهى الموعد</span>
                    @endif
                </div>
                <h3 class="font-bold text-slate-800">{{ $assignment->title }}</h3>
                <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                    <span>📚 {{ $assignment->subject->name }}</span>
                    <span>🏛️ {{ $assignment->classroom->name }}</span>
                    <span>🎯 {{ $assignment->total_marks }} درجة</span>
                    <span>📅 {{ $assignment->due_date->format('d M Y - H:i') }}</span>
                </div>
                <div class="flex items-center gap-3 mt-2 text-xs">
                    <span class="text-slate-500">التسليمات: <b class="text-primary">{{ $assignment->submissions_count }}</b></span>
                    <span class="text-slate-500">تم التصحيح: <b class="text-accent">{{ $assignment->graded_count }}</b></span>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('teacher.assignments.submissions',$assignment) }}" class="btn-outline text-xs py-1.5 px-3">التسليمات</a>
                <a href="{{ route('teacher.assignments.edit',$assignment) }}" class="btn-outline text-xs py-1.5 px-3">تعديل</a>
                <form method="POST" action="{{ route('teacher.assignments.destroy',$assignment) }}" onsubmit="return confirm('حذف الواجب؟')">
                    @csrf @method('DELETE')
                    <button class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-xl font-medium">حذف</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-12">
        <span class="text-5xl">📋</span>
        <p class="text-slate-400 mt-3 mb-4">لا توجد واجبات بعد</p>
        <a href="{{ route('teacher.assignments.create') }}" class="btn-primary">أنشئ أول واجب</a>
    </div>
    @endforelse
    {{ $assignments->links() }}
</div>
@endsection