@extends('layouts.auth')

@section('title', __('auth.register'))

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    {{-- Name Field --}}
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('app.name') }}
        </label>
        <div class="relative">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-3.5 text-gray-400">
                <i class="fas fa-user"></i>
            </span>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}"
                   class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="{{ __('app.name') }}"
                   required
                   autofocus>
        </div>
        @error('name')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
        @enderror
    </div>

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
                   required>
        </div>
        @error('email')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Phone Field --}}
    <div>
        <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('app.phone') }}
        </label>
        <div class="relative">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-3.5 text-gray-400">
                <i class="fas fa-phone"></i>
            </span>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone') }}"
                   class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="{{ __('app.phone') }}">
        </div>
        @error('phone')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Role Field --}}
    <div>
        <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('app.role') }}
        </label>
        <div class="relative">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-3.5 text-gray-400">
                <i class="fas fa-user-tag"></i>
            </span>
            <select id="role" 
                    name="role"
                    class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white"
                    required>
                <option value="">{{ __('app.select_role') }}</option>
                <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>{{ __('status.teacher') }}</option>
                <option value="parent" {{ old('role') === 'parent' ? 'selected' : '' }}>{{ __('status.parent') }}</option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>{{ __('status.student') }}</option>
            </select>
        </div>
        @error('role')
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
                   required>
        </div>
        @error('password')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Confirm Password Field --}}
    <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('auth.confirm_password') }}
        </label>
        <div class="relative">
            <span class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-3.5 text-gray-400">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation"
                   class="w-full {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="{{ __('auth.confirm_password') }}"
                   required>
        </div>
    </div>

    {{-- Register Button --}}
    <button type="submit" 
            class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 active:scale-95 shadow-lg">
        <i class="fas fa-user-plus {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
        {{ __('auth.register') }}
    </button>

    {{-- Login Link --}}
    <p class="text-center text-gray-600 dark:text-gray-400">
        {{ __('auth.have_account') }}
        <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            {{ __('auth.login') }}
        </a>
    </p>
</form>
@endsection