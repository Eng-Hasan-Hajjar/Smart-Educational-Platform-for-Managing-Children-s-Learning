@extends('layouts.app')
@section('title','إضافة مدرسة جديدة')
@section('page-title','🏫 إضافة مدرسة جديدة')
@section('content')
<div class="max-w-2xl mx-auto">
<form method="POST" action="{{ route('admin.schools.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="card space-y-5">
        <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3">معلومات المدرسة</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="label">اسم المدرسة (عربي) *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input" required>
            </div>
            <div>
                <label class="label">اسم المدرسة (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en') }}" class="input">
            </div>
            <div>
                <label class="label">البريد الإلكتروني *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input" required>
            </div>
            <div>
                <label class="label">رقم الهاتف</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input">
            </div>
            <div>
                <label class="label">المدينة</label>
                <input type="text" name="city" value="{{ old('city') }}" class="input">
            </div>
            <div>
                <label class="label">الدولة</label>
                <input type="text" name="country" value="{{ old('country','Syria') }}" class="input">
            </div>
            <div>
                <label class="label">خطة الاشتراك</label>
                <select name="subscription_plan" class="input">
                    <option value="basic">أساسي</option>
                    <option value="premium">مميز</option>
                    <option value="enterprise">مؤسسي</option>
                </select>
            </div>
            <div>
                <label class="label">تاريخ انتهاء الاشتراك</label>
                <input type="date" name="subscription_expires_at" value="{{ old('subscription_expires_at') }}" class="input">
            </div>
            <div>
                <label class="label">الحد الأقصى للطلاب</label>
                <input type="number" name="max_students" value="{{ old('max_students',500) }}" class="input" min="1">
            </div>
            <div>
                <label class="label">الحد الأقصى للمعلمين</label>
                <input type="number" name="max_teachers" value="{{ old('max_teachers',50) }}" class="input" min="1">
            </div>
            <div class="md:col-span-2">
                <label class="label">وصف المدرسة</label>
                <textarea name="description" rows="3" class="input">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="label">شعار المدرسة</label>
                <input type="file" name="logo" accept="image/*" class="input py-2">
            </div>
            <div>
                <label class="label">الحالة</label>
                <select name="status" class="input">
                    <option value="active">نشطة</option>
                    <option value="inactive">غير نشطة</option>
                </select>
            </div>
        </div>
    </div>
    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.schools.index') }}" class="btn-outline">إلغاء</a>
        <button type="submit" class="btn-primary">💾 حفظ المدرسة</button>
    </div>
</form>
</div>
@endsection