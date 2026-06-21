@extends('layouts.app')
@section('title','المعلمون')
@section('page-title','👨‍🏫 إدارة المعلمين')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-slate-500 text-sm">{{ $teachers->total() }} معلم</p>
        <a href="{{ route('school-admin.teachers.create') }}" class="btn-primary">➕ إضافة معلم</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($teachers as $teacher)
        <div class="card">
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $teacher->avatar_url }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-100" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-800 truncate">{{ $teacher->name }}</p>
                    <p class="text-xs text-slate-400">{{ $teacher->teacherProfile?->specialization ?? 'معلم' }}</p>
                </div>
                <span class="badge-{{ $teacher->status==='active'?'green':'red' }}">
                    {{ $teacher->status==='active'?'نشط':'غير نشط' }}
                </span>
            </div>

            <div class="space-y-1 text-xs text-slate-500 mb-4">
                <p>📧 {{ $teacher->email }}</p>
                @if($teacher->phone)<p>📞 {{ $teacher->phone }}</p>@endif
                <p>⭐ خبرة {{ $teacher->teacherProfile?->experience_years ?? 0 }} سنوات</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('school-admin.teachers.edit',$teacher) }}" class="flex-1 btn-outline text-xs text-center justify-center">تعديل</a>
                <a href="{{ route('teacher.reports.index') }}" class="flex-1 btn-outline text-xs text-center justify-center">التقارير</a>
            </div>
        </div>
        @empty
        <div class="col-span-3 card text-center py-12">
            <span class="text-5xl">👨‍🏫</span>
            <p class="text-slate-400 mt-3 mb-4">لا يوجد معلمون بعد</p>
            <a href="{{ route('school-admin.teachers.create') }}" class="btn-primary">إضافة أول معلم</a>
        </div>
        @endforelse
    </div>
    {{ $teachers->links() }}
</div>
@endsection