@extends('layouts.guest')
@section('title', __('app.forgot_password'))

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    {{-- توهج خلفية --}}
    <div class="absolute w-96 h-96 rounded-full bg-brand-400/15 blur-3xl -top-20 start-[-6rem] animate-pulse-glow"></div>
    <div class="absolute w-96 h-96 rounded-full bg-accent-400/15 blur-3xl bottom-0 end-0 animate-pulse-glow" style="animation-delay:1s"></div>
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle, var(--text-main) 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="w-full max-w-md relative z-10 animate-fade-up">
        <div class="text-center mb-8">
            <div class="logo-orb mx-auto mb-4 animate-float !text-3xl">🔐</div>
            <h1 class="text-2xl font-extrabold text-main mb-1.5">
                {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot your password?' }}
            </h1>
            <p class="text-muted text-sm">
                {{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين' : 'Enter your email and we\'ll send you a reset link' }}
            </p>
        </div>

        <div class="card animate-scale-in">
            @if(session('success'))
            <div class="!p-3.5 mb-5 flex items-center gap-3 rounded-xl border border-success-500/30 bg-success-50 text-success-600">
                <span class="text-lg">✅</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="!p-3.5 mb-5 flex items-center gap-3 rounded-xl border border-danger-500/30 bg-danger-50 text-danger-600">
                <span class="text-lg">⚠️</span>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="label">{{ __('app.email') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="input ps-11" placeholder="example@school.com">
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center !py-3.5">
                    📩 {{ app()->getLocale() === 'ar' ? 'إرسال رابط الاستعادة' : 'Send reset link' }}
                </button>
            </form>
        </div>

        <p class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-brand-500 hover:text-brand-700 font-semibold transition inline-flex items-center gap-1.5">
                <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('app.back') }} {{ app()->getLocale() === 'ar' ? 'لتسجيل الدخول' : 'to login' }}
            </a>
        </p>
    </div>
</div>
@endsection