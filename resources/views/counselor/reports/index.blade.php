@extends('layouts.app')
@section('title','التقارير التربوية')
@section('page-title','📝 التقارير التربوية')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-slate-500 text-sm">{{ $reports->total() }} تقرير</p>
        <a href="{{ route('counselor.reports.create') }}" class="btn-primary">📝 كتابة تقرير جديد</a>
    </div>

    @forelse($reports as $report)
    <div class="card">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ $report->student->avatar_url }}" class="w-10 h-10 rounded-full object-cover" alt="">
                <div>
                    <p class="font-bold text-slate-800">{{ $report->student->name }}</p>
                    <p class="text-xs text-slate-400">{{ $report->type_label }} · {{ $report->semester->name }} · {{ $report->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge-{{ $report->is_sent_to_parent ? 'green' : 'gray' }}">
                    {{ $report->is_sent_to_parent ? 'أُرسل لولي الأمر' : 'لم يُرسل' }}
                </span>
                <a href="{{ route('counselor.reports.show',$report) }}" class="btn-outline text-xs py-1.5 px-3">عرض</a>
            </div>
        </div>
        @if($report->counselor_notes)
        <p class="text-sm text-slate-600 mt-3 line-clamp-2">{{ $report->counselor_notes }}</p>
        @endif
    </div>
    @empty
    <div class="card text-center py-12">
        <span class="text-5xl">📝</span>
        <p class="text-slate-400 mt-3 mb-4">لا توجد تقارير بعد</p>
        <a href="{{ route('counselor.reports.create') }}" class="btn-primary">اكتب أول تقرير</a>
    </div>
    @endforelse
    {{ $reports->links() }}
</div>
@endsection