@extends('layouts.app')
@section('title','إضافة معلم')
@section('page-title','👨‍🏫 إضافة معلم جديد')
@section('content')
<div class="max-w-2xl mx-auto">
<form method="POST" action="{{ route('school.teachers.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="card space-y-5">
        <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3">البيانات الشخصية</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="label">الاسم الكامل *</label><input type="text" name="name" value="{{ old('name') }}" class="input" required></div>
            <div><label class="label">البريد الإلكتروني *</label><input type="email" name="email" value="{{ old('email') }}" class="input" required></div>
            <div><label class="label">كلمة المرور *</label><input type="password" name="password" class="input" required minlength="8"></div>
            <div><label class="label">رقم الهاتف</label><input type="text" name="phone" value="{{ old('phone') }}" class="input"></div>
            <div><label class="label">الجنس</label>
                <select name="gender" class="input">
                    <option value="male">ذكر</option>
                    <option value="female">أنثى</option>
                </select>
            </div>
            <div><label class="label">الصورة الشخصية</label><input type="file" name="avatar" accept="image/*" class="input py-2"></div>
        </div>
    </div>

    <div class="card space-y-5">
        <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3">البيانات المهنية</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="label">التخصص</label><input type="text" name="specialization" value="{{ old('specialization') }}" class="input" placeholder="مثال: الرياضيات"></div>
            <div><label class="label">المؤهل العلمي</label><input type="text" name="qualification" value="{{ old('qualification') }}" class="input" placeholder="مثال: بكالوريوس تربية"></div>
            <div><label class="label">سنوات الخبرة</label><input type="number" name="experience_years" value="{{ old('experience_years',0) }}" class="input" min="0"></div>
        </div>
        <div><label class="label">نبذة تعريفية</label><textarea name="bio" rows="3" class="input" placeholder="نبذة عن المعلم...">{{ old('bio') }}</textarea></div>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('school.teachers.index') }}" class="btn-outline">إلغاء</a>
        <button type="submit" class="btn-primary">💾 حفظ المعلم</button>
    </div>
</form>
</div>
@endsection