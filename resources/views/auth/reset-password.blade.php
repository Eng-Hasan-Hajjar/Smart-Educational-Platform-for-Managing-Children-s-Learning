@extends('layouts.guest')
@section('title', __('app.password'))

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden"
     x-data="{ showPassword: false, showConfirm: false }">

    {{-- توهج خلفية --}}
    <div class="absolute w-96 h-96 rounded-full bg-brand-400/15 blur-3xl -top-20 start-[-6rem] animate-pulse-glow"></div>
    <div class="absolute w-96 h-96 rounded-full bg-accent-400/15 blur-3xl bottom-0 end-0 animate-pulse-glow" style="animation-delay:1s"></div>
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle, var(--text-main) 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="w-full max-w-md relative z-10 animate-fade-up">
        <div class="text-center mb-8">
            <div class="logo-orb mx-auto mb-4 animate-float !text-3xl">🔑</div>
            <h1 class="text-2xl font-extrabold text-main mb-1.5">
                {{ app()->getLocale() === 'ar' ? 'تعيين كلمة مرور جديدة' : 'Set a new password' }}
            </h1>
            <p class="text-muted text-sm">
                {{ app()->getLocale() === 'ar' ? 'اختر كلمة مرور قوية لحسابك' : 'Choose a strong password for your account' }}
            </p>
        </div>

        <div class="card animate-scale-in">
            @if($errors->any())
            <div class="!p-3.5 mb-5 flex items-center gap-3 rounded-xl border border-danger-500/30 bg-danger-50 text-danger-600">
                <span class="text-lg">⚠️</span>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="label">{{ __('app.email') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ $email ?? old('email') }}" required
                               class="input ps-11" placeholder="example@school.com">
                    </div>
                </div>

                <div>
                    <label class="label">{{ app()->getLocale() === 'ar' ? 'كلمة المرور الجديدة' : 'New password' }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11V8a5 5 0 0110 0v3"/>
                            </svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" name="password" required minlength="8"
                               class="input ps-11 pe-11" placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-faint hover:text-brand-500 transition">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.584 10.587a2 2 0 002.829 2.83M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411M6.423 6.423A10.025 10.025 0 002.458 12c1.274 4.057 5.065 7 9.542 7a9.46 9.46 0 004.635-1.223"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-[11px] text-faint mt-1.5">
                        {{ app()->getLocale() === 'ar' ? '8 أحرف على الأقل' : 'At least 8 characters' }}
                    </p>
                </div>

                <div>
                    <label class="label">{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm password' }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                               class="input ps-11 pe-11" placeholder="••••••••">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-faint hover:text-brand-500 transition">
                            <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.584 10.587a2 2 0 002.829 2.83M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411M6.423 6.423A10.025 10.025 0 002.458 12c1.274 4.057 5.065 7 9.542 7a9.46 9.46 0 004.635-1.223"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center !py-3.5">
                    ✅ {{ app()->getLocale() === 'ar' ? 'حفظ كلمة المرور' : 'Save password' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection