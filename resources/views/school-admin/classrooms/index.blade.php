@extends('layouts.app')
@section('title','الفصول الدراسية')
@section('page-title','🏛️ إدارة الفصول الدراسية')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <p class="text-slate-500 text-sm">{{ $classrooms->total() }} فصل</p>
        <a href="{{ route('school.classrooms.create') }}" class="btn-primary">➕ إضافة فصل</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($classrooms as $classroom)
        <div class="card">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="font-bold text-slate-800">{{ $classroom->name }}</h3>
                    <p class="text-xs text-slate-400">{{ $classroom->academicLevel->name }}</p>
                </div>
                <span class="badge-{{ $classroom->is_active?'green':'gray' }}">
                    {{ $classroom->is_active?'نشط':'غير نشط' }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="font-black text-primary text-xl">{{ $classroom->students->count() }}</p>
                    <p class="text-xs text-slate-400">طالب</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="font-black text-secondary text-xl">{{ $classroom->capacity }}</p>
                    <p class="text-xs text-slate-400">الطاقة</p>
                </div>
            </div>

            <div class="w-full bg-slate-100 rounded-full h-2 mb-3">
                @php $pct = $classroom->capacity > 0 ? round($classroom->students->count() / $classroom->capacity * 100) : 0 @endphp
                <div class="rounded-full h-2 {{ $pct >= 90 ? 'bg-red-500' : 'bg-secondary' }}" style="width:{{ $pct }}%"></div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('school.classrooms.edit',$classroom) }}" class="flex-1 btn-outline text-xs text-center justify-center">تعديل</a>
                <form method="POST" action="{{ route('school.classrooms.destroy',$classroom) }}" onsubmit="return confirm('حذف هذا الفصل؟')">
                    @csrf @method('DELETE')
                    <button class="btn-danger text-xs">حذف</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 card text-center py-12">
            <span class="text-5xl">🏛️</span>
            <p class="text-slate-400 mt-3 mb-4">لا توجد فصول بعد</p>
            <a href="{{ route('school.classrooms.create') }}" class="btn-primary">إضافة أول فصل</a>
        </div>
        @endforelse
    </div>
    {{ $classrooms->links() }}
</div>
@endsection