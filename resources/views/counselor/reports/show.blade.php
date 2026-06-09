@extends('layouts.app')
@section('title','عرض التقرير')
@section('page-title','📋 تقرير تربوي')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('counselor.reports.index') }}" class="btn-outline text-sm">← رجوع</a>
        @if(!$report->is_sent_to_parent)
        <form method="POST" action="{{ route('counselor.reports.store') }}">
            @csrf
            <input type="hidden" name="report_id" value="{{ $report->id }}">
            <button class="btn-primary text-sm">📤 إرسال لولي الأمر</button>
        </form>
        @endif
    </div>

    <div class="card">
        <div class="flex items-center gap-4 mb-5 pb-4 border-b border-slate-100">
            <img src="{{ $report->student->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
            <div>
                <h2 class="text-xl font-black text-slate-800">{{ $report->student->name }}</h2>
                <p class="text-slate-400 text-sm">{{ $report->type_label }} · {{ $report->semester->name }} · {{ $report->semester->academicYear->name }}</p>
                <p class="text-xs text-slate-400 mt-1">بقلم: {{ $report->generatedBy->name }} · {{ $report->created_at->format('d M Y') }}</p>
            </div>
        </div>

        @if($report->subjects_data)
        <div class="mb-5">
            <h4 class="font-bold text-slate-700 mb-3">📊 أداء المواد:</h4>
            @foreach($report->subjects_data as $subj)
            <div class="flex items-center gap-3 mb-2">
                <p class="text-sm text-slate-700 w-32 truncate">{{ $subj['subject'] ?? '—' }}</p>
                <div class="flex-1"><div class="w-full bg-slate-100 rounded-full h-2"><div class="bg-secondary rounded-full h-2" style="width:{{ $subj['average_score'] ?? 0 }}%"></div></div></div>
                <span class="text-xs font-bold text-primary w-10 text-left">{{ round($subj['average_score'] ?? 0) }}%</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="space-y-4">
            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                <p class="text-xs font-bold text-blue-600 mb-2">🧑‍💼 الملاحظات التربوية:</p>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $report->counselor_notes }}</p>
            </div>
            @if($report->recommendations)
            <div class="p-4 bg-green-50 rounded-2xl border border-green-100">
                <p class="text-xs font-bold text-green-600 mb-2">💡 التوصيات:</p>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $report->recommendations }}</p>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
            <span class="badge-{{ $report->is_sent_to_parent ? 'green' : 'gray' }}">
                {{ $report->is_sent_to_parent ? '✅ أُرسل لولي الأمر ' . $report->sent_at?->format('d M Y') : '⏳ لم يُرسل بعد' }}
            </span>
        </div>
    </div>
</div>
@endsection