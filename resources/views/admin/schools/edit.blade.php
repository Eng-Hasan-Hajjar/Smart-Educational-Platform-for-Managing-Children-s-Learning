@extends('layouts.app')
@section('title','تعديل المدرسة')
@section('page-title','✏️ تعديل — {{ $school->name }}')
@section('content')
<div class="max-w-2xl mx-auto">
<form method="POST" action="{{ route('admin.schools.update', $school) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf @method('PUT')
    <div class="card space-y-5">
        <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3">تعديل معلومات المدرسة</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="label">اسم المدرسة (عربي) *</label>
                <input type="text" name="name" value="{{ old('name',$school->name) }}" class="input" required>
            </div>
            <div>
                <label class="label">اسم المدرسة (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en',$school->name_en) }}" class="input">
            </div>
            <div>
                <label class="label">البريد الإلكتروني *</label>
                <input type="email" name="email" value="{{ old('email',$school->email) }}" class="input" required>
            </div>
            <div>
                <label class="label">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone',$school->phone) }}" class="input">
            </div>
            <div>
                <label class="label">المدينة</label>
                <input type="text" name="city" value="{{ old('city',$school->city) }}" class="input">
            </div>
            <div>
                <label class="label">الدولة</label>
                <input type="text" name="country" value="{{ old('country',$school->country) }}" class="input">
            </div>
            <div>
                <label class="label">خطة الاشتراك</label>
                <select name="subscription_plan" class="input">
                    @foreach(['basic'=>'أساسي','premium'=>'مميز','enterprise'=>'مؤسسي'] as $val=>$label)
                    <option value="{{ $val }}" {{ $school->subscription_plan===$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">تاريخ انتهاء الاشتراك</label>
                <input type="date" name="subscription_expires_at" value="{{ old('subscription_expires_at',$school->subscription_expires_at?->format('Y-m-d')) }}" class="input">
            </div>
            <div>
                <label class="label">الحد الأقصى للطلاب</label>
                <input type="number" name="max_students" value="{{ old('max_students',$school->max_students) }}" class="input">
            </div>
            <div>
                <label class="label">الحد الأقصى للمعلمين</label>
                <input type="number" name="max_teachers" value="{{ old('max_teachers',$school->max_teachers) }}" class="input">
            </div>
            <div class="md:col-span-2">
                <label class="label">وصف المدرسة</label>
                <textarea name="description" rows="3" class="input">{{ old('description',$school->description) }}</textarea>
            </div>
            <div>
                <label class="label">الشعار الحالي</label>
                <img src="{{ $school->logo_url }}" class="w-16 h-16 rounded-xl object-cover mb-2 border border-slate-100">
                <input type="file" name="logo" accept="image/*" class="input py-2">
            </div>
            <div>
                <label class="label">الحالة</label>
                <select name="status" class="input">
                    @foreach(['active'=>'نشطة','inactive'=>'غير نشطة','suspended'=>'موقوفة'] as $val=>$label)
                    <option value="{{ $val }}" {{ $school->status===$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.schools.index') }}" class="btn-outline">إلغاء</a>
        <button type="submit" class="btn-primary">💾 حفظ التغييرات</button>
    </div>
</form>
</div>
@endsection