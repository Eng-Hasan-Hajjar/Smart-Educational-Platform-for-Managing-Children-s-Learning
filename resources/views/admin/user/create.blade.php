@extends('layouts.app')
@section('title','إضافة مستخدم')
@section('page-title','👤 إضافة مستخدم جديد')
@section('content')
<div class="max-w-2xl mx-auto">
<form method="POST" action="{{ route('admin.users.store') }}" class="card space-y-5">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="label">الاسم الكامل *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input" required>
        </div>
        <div>
            <label class="label">البريد الإلكتروني *</label>
            <input type="email" name="email" value="{{ old('email') }}" class="input" required>
        </div>
        <div>
            <label class="label">كلمة المرور *</label>
            <input type="password" name="password" class="input" required minlength="8">
        </div>
        <div>
            <label class="label">تأكيد كلمة المرور *</label>
            <input type="password" name="password_confirmation" class="input" required>
        </div>
        <div>
            <label class="label">الدور *</label>
            <select name="role" class="input" required>
                <option value="">— اختر الدور —</option>
                @foreach(['super_admin'=>'مدير النظام','school_admin'=>'مدير مدرسة','counselor'=>'موجه تربوي','teacher'=>'معلم','parent'=>'ولي أمر','student'=>'طالب'] as $val=>$label)
                <option value="{{ $val }}" {{ old('role')===$val?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">المدرسة</label>
            <select name="school_id" class="input">
                <option value="">— بدون مدرسة —</option>
                @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ old('school_id')===$school->id?'selected':'' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">رقم الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="input">
        </div>
        <div>
            <label class="label">الجنس</label>
            <select name="gender" class="input">
                <option value="">— غير محدد —</option>
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
            </select>
        </div>
    </div>
    <div class="flex justify-end gap-3">
        <a href="{{ route('admin.users.index') }}" class="btn-outline">إلغاء</a>
        <button type="submit" class="btn-primary">💾 إنشاء المستخدم</button>
    </div>
</form>
</div>
@endsection