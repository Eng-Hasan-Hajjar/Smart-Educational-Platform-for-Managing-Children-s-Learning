@extends('layouts.guest')
@section('title', __('app.login'))

@section('content')
<div class="min-h-screen flex flex-col lg:flex-row" x-data="{
    showPassword: false,
    loading: false,
    fillDemo(email) {
        this.$refs.emailInput.value = email;
        this.$refs.passwordInput.value = 'password';
    }
}">

    {{-- ══════════ Brand Panel ══════════ --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12"
         style="background: linear-gradient(150deg, var(--brand-600), var(--brand-700) 55%, var(--bg-sidebar-to))">

        {{-- توهج زخرفي --}}
        <div class="absolute w-72 h-72 rounded-full bg-accent-400/25 blur-3xl -top-10 start-[-4rem] animate-pulse-glow"></div>
        <div class="absolute w-96 h-96 rounded-full bg-brand-400/20 blur-3xl bottom-0 end-0 animate-pulse-glow" style="animation-delay:1s"></div>

        {{-- شبكة نقطية --}}
        <div class="absolute inset-0 opacity-[0.06]"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 28px 28px;"></div>

        {{-- أيقونات عائمة --}}
        <div class="absolute top-16 start-16 text-5xl animate-float opacity-70 select-none">📚</div>
        <div class="absolute top-28 end-20 text-4xl animate-float opacity-60 select-none" style="animation-delay:.6s">✏️</div>
        <div class="absolute bottom-40 start-20 text-4xl animate-float opacity-60 select-none" style="animation-delay:1.2s">🔬</div>
        <div class="absolute bottom-24 end-16 text-5xl animate-float opacity-70 select-none" style="animation-delay:1.8s">🎨</div>
        <div class="absolute top-1/2 start-1/3 text-3xl animate-float opacity-50 select-none" style="animation-delay:.3s">⭐</div>

        <div class="relative z-10 max-w-md animate-fade-up">
            <div class="logo-orb mx-auto mb-6 !w-20 !h-20 !text-4xl">🎓</div>

            <h1 class="text-white text-4xl font-extrabold mb-3 text-center">{{ __('app.platform_name') }}</h1>

            <p class="text-white/70 text-base leading-relaxed mb-8 text-center">
                @if($__locale === 'ar' ?? app()->getLocale() === 'ar')
                    نظام إدارة مدرسة إلكترونية ذكي، يجمع بين التعليم التفاعلي ومتابعة الأداء وتحفيز الطلاب.
                @else
                    A smart e-school platform combining interactive learning, performance tracking, and student motivation.
                @endif
            </p>

            <div class="space-y-3">
                @php
                    $features = app()->getLocale() === 'ar'
                        ? ['دروس تفاعلية بالفيديو والصوت والنص', 'تحليلات وتوصيات بالذكاء الاصطناعي', 'نظام نقاط وشارات تحفيزي', 'تواصل مباشر بين المعلم وولي الأمر']
                        : ['Interactive video, audio & text lessons', 'AI-powered analytics & recommendations', 'Gamified points & badges system', 'Direct teacher-parent communication'];
                @endphp
                @foreach($features as $i => $feature)
                <div class="flex items-center gap-3 text-white/85 animate-slide" style="animation-delay: {{ 0.15 + $i * 0.08 }}s">
                    <span class="w-6 h-6 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0 text-xs font-bold">✓</span>
                    <span class="text-sm">{{ $feature }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════ Form Panel ══════════ --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-app min-h-screen">
        <div class="w-full max-w-md animate-fade-up">

            {{-- شعار للجوال --}}
            <div class="lg:hidden text-center mb-8">
                <div class="logo-orb mx-auto mb-3 animate-float">🎓</div>
                <h1 class="text-xl font-extrabold text-main">{{ __('app.platform_name') }}</h1>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-main mb-1.5">{{ __('app.welcome_back') }} 👋</h2>
                <p class="text-muted text-sm">
                    @if(app()->getLocale() === 'ar')
                        سجّل الدخول للوصول إلى لوحة التحكم الخاصة بك
                    @else
                        Sign in to access your dashboard
                    @endif
                </p>
            </div>

            {{-- تنبيهات --}}
            @if(session('success'))
            <div class="card !p-3.5 mb-5 flex items-center gap-3 border-success-500/30 bg-success-50 text-success-600 animate-fade-up">
                <span class="text-lg">✅</span>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="card !p-3.5 mb-5 flex items-center gap-3 border-danger-500/30 bg-danger-50 text-danger-600 animate-fade-up">
                <span class="text-lg">⚠️</span>
                <span class="text-sm font-medium">{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" @submit="loading = true" class="space-y-5">
                @csrf

                {{-- البريد الإلكتروني --}}
                <div>
                    <label class="label">{{ __('app.email') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input x-ref="emailInput" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="input ps-11" placeholder="example@school.com">
                    </div>
                </div>

                {{-- كلمة المرور --}}
                <div>
                    <label class="label">{{ __('app.password') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 .552-.448 1-1 1H6a1 1 0 01-1-1V8a5 5 0 1110 0v3z"/>
                                <rect x="3" y="11" width="18" height="10" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                            </svg>
                        </span>
                        <input x-ref="passwordInput" :type="showPassword ? 'text' : 'password'" name="password" required
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
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-muted cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-bds">
                        {{ __('app.remember_me') }}
                    </label>
                    <a href="{{ route('password.request') }}" class="text-brand-500 hover:text-brand-700 font-semibold transition">
                        {{ __('app.forgot_password') }}
                    </a>
                </div>

                <button type="submit" :disabled="loading" class="btn-primary w-full justify-center !py-3.5 text-base">
                    <span x-show="!loading" class="flex items-center gap-2">
                        {{ __('app.login') }}
                        <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('app.loading') }}
                    </span>
                </button>
            </form>

            {{-- حسابات تجريبية --}}
            <div x-data="{ open: false }" class="mt-8">
                <button @click="open = !open" type="button"
                        class="w-full flex items-center justify-center gap-1.5 text-xs text-muted hover:text-brand-500 font-semibold transition">
                    🔑 {{ app()->getLocale() === 'ar' ? 'حسابات تجريبية' : 'Demo accounts' }}
                    <svg :class="open ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition class="mt-3 card !p-2 grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @php
                        $demos = [
                            ['email' => 'admin@nour.edu',       'icon' => '🛡️', 'label_ar' => 'مدير النظام',   'label_en' => 'Super Admin'],
                            ['email' => 'schooladmin@nour.edu', 'icon' => '🏫', 'label_ar' => 'مدير المدرسة', 'label_en' => 'School Admin'],
                            ['email' => 'teacher@nour.edu',     'icon' => '👨‍🏫', 'label_ar' => 'معلم',         'label_en' => 'Teacher'],
                            ['email' => 'counselor@nour.edu',   'icon' => '🧑‍💼', 'label_ar' => 'موجه تربوي',   'label_en' => 'Counselor'],
                            ['email' => 'parent@nour.edu',      'icon' => '👨‍👩‍👧', 'label_ar' => 'ولي أمر',      'label_en' => 'Parent'],
                            ['email' => 'student@nour.edu',     'icon' => '👨‍🎓', 'label_ar' => 'طالب',         'label_en' => 'Student'],
                        ];
                    @endphp
                    @foreach($demos as $demo)
                    <button type="button" @click="fillDemo('{{ $demo['email'] }}')"
                            class="flex flex-col items-center gap-1 p-2.5 rounded-xl hover:bg-hover transition group">
                        <span class="text-xl group-hover:scale-110 transition-transform">{{ $demo['icon'] }}</span>
                        <span class="text-[11px] text-muted font-medium">{{ app()->getLocale() === 'ar' ? $demo['label_ar'] : $demo['label_en'] }}</span>
                    </button>
                    @endforeach
                </div>
                <p x-show="open" x-cloak class="text-center text-[11px] text-faint mt-2">
                    {{ app()->getLocale() === 'ar' ? 'كلمة المرور لجميع الحسابات: password' : 'Password for all accounts: password' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection