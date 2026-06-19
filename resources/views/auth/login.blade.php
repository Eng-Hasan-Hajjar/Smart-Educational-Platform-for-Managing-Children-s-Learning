@extends('layouts.auth')

@section('title', __('auth.login'))

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
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

    {{-- Password Field --}}
    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('app.password') }}
        </label>
        <div class="relative">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-3.5 text-gray-400">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" 
                   id="password" 
                   name="password"
                   class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="{{ __('app.password') }}"
                   required
                   autocomplete="current-password">
        </div>
        @error('password')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Remember Me & Forgot Password --}}
    <div class="flex {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} items-center justify-between">
        <label for="remember" class="flex {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row' }} items-center cursor-pointer">
            <input type="checkbox" 
                   id="remember" 
                   name="remember" 
                   {{ old('remember') ? 'checked' : '' }}
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600">
            <span class="text-sm text-gray-600 dark:text-gray-400 {{ app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3' }}">
                {{ __('auth.remember_me') }}
            </span>
        </label>
        <a href="{{ route('forgot-password') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            {{ __('auth.forgot_password') }}
        </a>
    </div>

    {{-- Login Button --}}
    <button type="submit" 
            class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 active:scale-95 shadow-lg">
        <i class="fas fa-sign-in-alt {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
        {{ __('auth.login') }}
    </button>

    {{-- Sign Up Link --}}
    <p class="text-center text-gray-600 dark:text-gray-400">
        {{ __('auth.dont_have_account') }}
        <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            {{ __('auth.register') }}
        </a>
    </p>
</form>
@endsection