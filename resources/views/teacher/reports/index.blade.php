@extends('layouts.app')
@section('title','تقارير الطلاب')
@section('page-title','📊 تقارير طلابي')
@section('content')
<div class="space-y-4">
    <div class="card p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="label">الفصل</label>
                <select name="classroom_id" class="input">
                    @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ request('classroom_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">🔍 عرض التقرير</button>
            </div>
            @if($selectedClassroom)
            <div class="flex items-end">
                <a href="{{ route('ai.analyze',$selectedClassroom->students->first()) }}" class="btn-outline w-full text-center justify-center">🤖 تحديث التحليل</a>
            </div>
            @endif
        </form>
    </div>

    @if($selectedClassroom && count($students))
    <div class="card overflow-hidden p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الطالب</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">نسبة الحضور</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">متوسط الدرجات</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">دروس مكتملة</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الحالة</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">التفاصيل</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($students as $row)
                @php $s = $row['student']; @endphp
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ $s->avatar_url }}" class="w-8 h-8 rounded-full object-cover" alt="">
                            <p class="font-medium text-slate-800">{{ $s->name }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="{{ $row['attendance_rate'] >= 75 ? 'text-green-600' : 'text-red-600' }} font-bold">
                            {{ $row['attendance_rate'] }}%
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="{{ $row['avg_score'] >= 60 ? 'text-blue-600' : 'text-red-600' }} font-bold">
                            {{ $row['avg_score'] }}%
                        </span>
                    </td>
                    <td class="py-3 px-4 text-slate-600">{{ $row['lessons_done'] }}</td>
                    <td class="py-3 px-4">
                        <span class="badge-{{ $s->studentProfile?->status_color ?? 'gray' }}">
                            {{ $s->studentProfile?->status_label ?? '—' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('teacher.reports.student',$s) }}" class="text-secondary hover:underline text-xs">عرض التفاصيل</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection