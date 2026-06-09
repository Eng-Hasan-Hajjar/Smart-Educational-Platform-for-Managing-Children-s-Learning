@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="auth-card">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-950">تسجيل الدخول</h2>
        <p class="mt-3 text-sm font-semibold text-slate-500">
            أدخل بياناتك للوصول إلى لوحة التحكم المناسبة لدورك.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-2xl bg-red-50 p-4 text-sm font-bold text-red-600">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="auth-label">البريد الإلكتروني</label>
            <input class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label class="auth-label">كلمة المرور</label>
            <input class="auth-input" type="password" name="password" required>
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 font-bold text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-cyan-600">
                تذكرني
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="font-extrabold text-cyan-600 hover:text-cyan-700">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full">
            دخول المنصة
        </button>

        <p class="text-center text-sm font-bold text-slate-500">
            لا تملك حسابًا؟
            <a href="{{ route('register') }}" class="text-cyan-600 hover:text-cyan-700">
                إنشاء حساب جديد
            </a>
        </p>
    </form>
</div>
@endsection