@extends('layouts.app')
@section('title','الاختبارات')
@section('page-title','📝 إدارة الاختبارات')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-slate-500 text-sm">{{ $quizzes->total() }} اختبار</p>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn-primary">➕ اختبار جديد</a>
    </div>

    @forelse($quizzes as $quiz)
    <div class="card">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge-{{ $quiz->status==='published'?'green':'gray' }}">{{ $quiz->status==='published'?'منشور':'مسودة' }}</span>
                    <span class="badge-blue">{{ $quiz->type_label }}</span>
                </div>
                <h3 class="font-bold text-slate-800">{{ $quiz->title }}</h3>
                <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                    <span>❓ {{ $quiz->questions_count }} سؤال</span>
                    <span>🎯 {{ $quiz->total_marks }} درجة</span>
                    <span>⏱ {{ $quiz->duration_minutes ?? '—' }} دقيقة</span>
                    <span>🔄 {{ $quiz->max_attempts }} محاولة</span>
                    <span>📊 {{ $quiz->attempts->count() }} طالب أدّاه</span>
                </div>
                @if($quiz->available_from || $quiz->available_until)
                <p class="text-xs text-slate-400 mt-1">
                    📅 {{ $quiz->available_from?->format('d M H:i') ?? '—' }} → {{ $quiz->available_until?->format('d M H:i') ?? '—' }}
                </p>
                @endif
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('teacher.quizzes.edit',$quiz) }}" class="btn-outline text-xs py-1.5 px-3">تعديل</a>
                <form method="POST" action="{{ route('teacher.quizzes.publish',$quiz) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs {{ $quiz->status==='published'?'bg-yellow-100 text-yellow-700':'bg-green-100 text-green-700' }} px-3 py-1.5 rounded-xl font-medium">
                        {{ $quiz->status==='published'?'إخفاء':'نشر' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('teacher.quizzes.destroy',$quiz) }}" onsubmit="return confirm('حذف الاختبار؟')">
                    @csrf @method('DELETE')
                    <button class="text-xs bg-red-100 text-red-700 px-3 py-1.5 rounded-xl font-medium">حذف</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-12">
        <span class="text-5xl">📝</span>
        <p class="text-slate-400 mt-3 mb-4">لا توجد اختبارات بعد</p>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn-primary">أنشئ أول اختبار</a>
    </div>
    @endforelse
    {{ $quizzes->links() }}
</div>
@endsection