@extends('layouts.app')
@section('title','الطلاب')
@section('page-title','👨‍🎓 إدارة الطلاب')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-slate-500 text-sm">{{ $students->total() }} طالب</p>
        <a href="{{ route('school-admin.students.create') }}" class="btn-primary">➕ تسجيل طالب</a>
    </div>

    <div class="card p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" class="input" placeholder="اسم أو بريد الطالب...">
            <select name="level_id" class="input">
                <option value="">كل المراحل</option>
                @foreach($levels as $level)
                <option value="{{ $level->id }}" {{ request('level_id')==$level->id?'selected':'' }}>{{ $level->name }}</option>
                @endforeach
            </select>
            <select name="status" class="input">
                <option value="">كل الحالات</option>
                @foreach(['excellent'=>'ممتاز','good'=>'جيد','average'=>'متوسط','needs_support'=>'يحتاج دعماً','at_risk'=>'في خطر'] as $v=>$l)
                <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">🔍 بحث</button>
        </form>
    </div>

    <div class="card overflow-hidden p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الطالب</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">المرحلة</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الفصل</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الحالة</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($students as $student)
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                            <div>
                                <p class="font-medium text-slate-800">{{ $student->name }}</p>
                                <p class="text-xs text-slate-400">{{ $student->studentProfile?->student_number ?? $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600 text-xs">{{ $student->studentProfile?->academicLevel?->name ?? '—' }}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">{{ $student->classrooms->first()?->name ?? '—' }}</td>
                    <td class="py-3 px-4">
                        <span class="badge-{{ $student->studentProfile?->status_color ?? 'gray' }}">
                            {{ $student->studentProfile?->status_label ?? '—' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('school-admin.students.edit',$student) }}" class="text-secondary hover:underline text-xs">تعديل</a>
                            <form method="POST" action="{{ route('school-admin.students.toggle-status',$student) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs {{ $student->status==='active'?'text-red-500':'text-green-600' }} hover:underline">
                                    {{ $student->status==='active'?'تعطيل':'تفعيل' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-8 text-slate-400">لا يوجد طلاب</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $students->links() }}
</div>
@endsection