@extends('layouts.app')
@section('title','التقارير')
@section('page-title','📊 تقارير المدرسة')
@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card text-center">
            <p class="text-3xl font-black text-primary">{{ $classrooms->count() }}</p>
            <p class="text-slate-500 text-sm mt-1">فصل دراسي</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-black text-danger">{{ $atRiskStudents->count() }}</p>
            <p class="text-slate-500 text-sm mt-1">طالب في خطر</p>
        </div>
        <div class="card text-center">
            <p class="text-3xl font-black text-accent">{{ $topStudents->count() }}</p>
            <p class="text-slate-500 text-sm mt-1">طالب متميز</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4">🚨 طلاب يحتاجون تدخلاً عاجلاً</h3>
            <div class="space-y-2">
                @forelse($atRiskStudents as $s)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
                    <img src="{{ $s->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-slate-800 truncate">{{ $s->name }}</p>
                        <p class="text-xs text-slate-400">{{ $s->classrooms->first()?->name ?? '—' }}</p>
                    </div>
                    <span class="badge-red">في خطر</span>
                </div>
                @empty
                <div class="text-center py-6"><span class="text-3xl">🎉</span><p class="text-slate-400 text-sm mt-2">لا يوجد طلاب في خطر</p></div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 class="font-bold text-slate-800 mb-4">⭐ الطلاب المتميزون</h3>
            <div class="space-y-2">
                @forelse($topStudents as $i => $s)
                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-100">
                    <span class="w-7 h-7 rounded-xl bg-green-200 text-green-800 flex items-center justify-center font-black text-xs">{{ $i+1 }}</span>
                    <img src="{{ $s->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm text-slate-800 truncate">{{ $s->name }}</p>
                        <p class="text-xs text-slate-400">معدل {{ round($s->studentProfile?->gpa ?? 0) }}%</p>
                    </div>
                    <span class="badge-green">ممتاز</span>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-6">لا توجد بيانات بعد</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4">🏛️ إحصاءات الفصول</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-right py-3 px-4 text-slate-500 font-medium">الفصل</th>
                        <th class="text-right py-3 px-4 text-slate-500 font-medium">المرحلة</th>
                        <th class="text-right py-3 px-4 text-slate-500 font-medium">عدد الطلاب</th>
                        <th class="text-right py-3 px-4 text-slate-500 font-medium">نسبة الامتلاء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($classrooms as $c)
                    @php $pct = $c->capacity > 0 ? round($c->students_count / $c->capacity * 100) : 0 @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-medium text-slate-800">{{ $c->name }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $c->academicLevel->name }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $c->students_count }} / {{ $c->capacity }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-20 bg-slate-100 rounded-full h-2">
                                    <div class="rounded-full h-2 {{ $pct>=90?'bg-red-500':'bg-secondary' }}" style="width:{{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-slate-500">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection