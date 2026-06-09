@extends('layouts.app')
@section('title','الجداول الدراسية')
@section('page-title','📅 الجداول الدراسية')
@section('content')
<div class="space-y-4">
    <div class="card p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="label">الفصل الدراسي</label>
                <select name="classroom_id" class="input">
                    @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ request('classroom_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">عرض الجدول</button>
            </div>
            <div class="flex items-end">
                <a href="{{ route('school.schedules.create') }}" class="btn-outline w-full text-center justify-center">➕ إضافة حصة</a>
            </div>
        </form>
    </div>

    @if(isset($schedules))
    <div class="overflow-x-auto">
        <div class="grid grid-cols-5 gap-3 min-w-[700px]">
            @foreach([0=>'الأحد',1=>'الاثنين',2=>'الثلاثاء',3=>'الأربعاء',4=>'الخميس'] as $day=>$name)
            <div class="card p-3">
                <h4 class="font-bold text-slate-700 text-center mb-3 pb-2 border-b border-slate-100 text-sm">{{ $name }}</h4>
                <div class="space-y-2">
                    @forelse($schedules->where('day_of_week',$day) as $schedule)
                    <div class="p-2.5 rounded-xl text-xs border" style="background:{{ $schedule->subject->color ?? '#2E86C1' }}15; border-color:{{ $schedule->subject->color ?? '#2E86C1' }}30">
                        <p class="font-bold text-slate-800">{{ $schedule->subject->name }}</p>
                        <p class="text-slate-500">{{ $schedule->timeSlot->start_time }} - {{ $schedule->timeSlot->end_time }}</p>
                        <p class="text-slate-400 truncate">{{ $schedule->teacher->name }}</p>
                        <form method="POST" action="{{ route('school.schedules.destroy',$schedule) }}" class="mt-1" onsubmit="return confirm('حذف هذه الحصة؟')">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 font-medium">حذف</button>
                        </form>
                    </div>
                    @empty
                    <p class="text-center text-slate-300 text-xs py-4">—</p>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection