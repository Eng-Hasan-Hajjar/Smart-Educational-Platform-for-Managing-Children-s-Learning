@extends('layouts.app')
@section('title','الإعلانات')
@section('page-title','📢 الإعلانات')
@section('content')
<div class="space-y-4">
    @can('create', App\Models\Announcement::class)
    <div class="card">
        <h3 class="font-bold text-slate-800 mb-4">➕ إنشاء إعلان جديد</h3>
        <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2"><label class="label">العنوان *</label><input type="text" name="title" class="input" required></div>
                <div><label class="label">النوع</label>
                    <select name="type" class="input">
                        <option value="general">عام</option>
                        <option value="academic">أكاديمي</option>
                        <option value="urgent">عاجل</option>
                        <option value="event">حدث</option>
                    </select>
                </div>
                <div><label class="label">المستهدفون</label>
                    <select name="target_type" class="input">
                        <option value="all">الجميع</option>
                        <option value="teachers">المعلمون</option>
                        <option value="students">الطلاب</option>
                        <option value="parents">أولياء الأمور</option>
                    </select>
                </div>
                <div class="md:col-span-2"><label class="label">النص *</label><textarea name="body" rows="4" class="input" required></textarea></div>
                <div><label class="label">مرفق (اختياري)</label><input type="file" name="attachment" class="input py-2"></div>
                <div class="flex items-end gap-4">
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="send_notification" value="1" checked class="rounded"><span class="text-sm text-slate-600">إرسال إشعار</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_pinned" value="1" class="rounded"><span class="text-sm text-slate-600">تثبيت</span></label>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">📢 نشر الإعلان</button>
            </div>
        </form>
    </div>
    @endcan

    @forelse($announcements as $announcement)
    <div class="card {{ $announcement->type==='urgent'?'border-red-200 bg-red-50/30':'' }}">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                {{ $announcement->type==='urgent'?'bg-red-100':($announcement->type==='academic'?'bg-blue-100':($announcement->type==='event'?'bg-purple-100':'bg-gray-100')) }}">
                {{ $announcement->type==='urgent'?'🚨':($announcement->type==='academic'?'📚':($announcement->type==='event'?'📅':'📢')) }}
            </div>
            <div class="flex-1">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        @if($announcement->is_pinned)<span class="text-xs text-amber-600 font-bold">📌 مثبّت</span>@endif
                        <h3 class="font-bold text-slate-800 mt-0.5">{{ $announcement->title }}</h3>
                        <p class="text-xs text-slate-400 mt-1">{{ $announcement->creator->name }} · {{ $announcement->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge-{{ $announcement->type_color }} flex-shrink-0">{{ $announcement->type_label }}</span>
                </div>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $announcement->body }}</p>
                @if($announcement->attachment_url)
                <a href="{{ $announcement->attachment_url }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs text-secondary hover:underline">📎 مرفق</a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-12">
        <span class="text-5xl">📢</span>
        <p class="text-slate-400 mt-3">لا توجد إعلانات</p>
    </div>
    @endforelse
    {{ $announcements->links() }}
</div>
@endsection