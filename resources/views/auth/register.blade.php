@extends('layouts.auth')

@section('title', 'إنشاء حساب')

@section('content')
<div class="auth-card">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-950">إنشاء حساب جديد</h2>
        <p class="mt-3 text-sm font-semibold text-slate-500">
            اختر الدور المناسب ليتم توجيهك إلى لوحة التحكم الصحيحة.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-2xl bg-red-50 p-4 text-sm font-bold text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label class="auth-label">الاسم الكامل</label>
            <input class="auth-input" type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label class="auth-label">البريد الإلكتروني</label>
            <input class="auth-input" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label class="auth-label">نوع الحساب</label>
            <select class="auth-input" name="role" required>
                <option value="">اختر نوع الحساب</option>
                <option value="student" @selected(old('role') === 'student')>طالب</option>
                <option value="parent" @selected(old('role') === 'parent')>ولي أمر</option>
                <option value="teacher" @selected(old('role') === 'teacher')>معلم</option>
                <option value="counselor" @selected(old('role') === 'counselor')>موجه تربوي</option>
                <option value="school_admin" @selected(old('role') === 'school_admin')>مدير مدرسة</option>
            </select>
        </div>

        <div>
            <label class="auth-label">كلمة المرور</label>
            <input class="auth-input" type="password" name="password" required>
        </div>

        <div>
            <label class="auth-label">تأكيد كلمة المرور</label>
            <input class="auth-input" type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn-primary w-full">
            إنشاء الحساب
        </button>

        <p class="text-center text-sm font-bold text-slate-500">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="text-cyan-600 hover:text-cyan-700">
                تسجيل الدخول
            </a>
        </p>
    </form>
</div>
@endsection