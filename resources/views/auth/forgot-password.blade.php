@extends('layouts.auth')

@section('title', __('auth.forgot_password'))

@section('content')
<div class="text-center mb-8">
    <p class="text-gray-600 dark:text-gray-400 text-sm">
        {{ __('auth.reset_instructions') ?? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور.' }}
    </p>
</div>

<form method="POST" action="{{ route('send-reset-link') }}" class="space-y-6">
    @csrf

    {{-- Email Field --}}
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('app.email') }}
        </label>
        <div class="relative">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-3.5 text-gray-400">
                <i class="fas fa-envelope"></i>
            </span>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="{{ __('app.email') }}"
                   required
                   autocomplete="email">
        </div>
        @error('email')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit Button --}}
    <button type="submit" 
            class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 active:scale-95 shadow-lg">
        <i class="fas fa-paper-plane {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
        {{ __('auth.send_reset_link') }}
    </button>

    {{-- Back to Login Link --}}
    <p class="text-center text-gray-600 dark:text-gray-400 text-sm">
        <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            <i class="fas fa-arrow-left {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
            {{ __('auth.back_to_login') ?? 'العودة إلى تسجيل الدخول' }}
        </a>
    </p>
</form>
@endsection