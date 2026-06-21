@extends('layouts.app')
@section('title', __('admin.add_user'))
@section('page-title', __('admin.add_user'))
@section('page-subtitle', __('admin.add_user_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main">{{ __('admin.user_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.full_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.password') }} *</label>
                    <input type="password" name="password" required minlength="8" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.confirm_password') }} *</label>
                    <input type="password" name="password_confirmation" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.role') }} *</label>
                    <select name="role" required class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach(['super_admin'=>__('app.super_admin'),'school_admin'=>__('app.school_admin'),'counselor'=>__('app.counselor'),'teacher'=>__('app.teacher'),'parent'=>__('app.parent'),'student'=>__('app.student')] as $v=>$l)
                        <option value="{{ $v }}" {{ old('role') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('admin.school') }}</label>
                    <select name="school_id" class="input">
                        <option value="">{{ __('admin.no_school') }}</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.gender') }}</label>
                    <select name="gender" class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="male">{{ __('schooladmin.male') }}</option>
                        <option value="female">{{ __('schooladmin.female') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 {{ __('admin.create_user') }}</span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    {{ __('app.loading') }}
                </span>
            </button>
        </div>
    </form>
</div>
@endsection