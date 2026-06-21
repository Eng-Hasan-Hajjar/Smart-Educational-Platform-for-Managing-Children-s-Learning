@extends('layouts.app')
@section('title', __('admin.add_school'))
@section('page-title', __('admin.add_school'))
@section('page-subtitle', __('admin.add_school_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('admin.schools.store') }}" enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">🏫</span>
                <h3 class="font-bold text-main">{{ __('admin.school_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('admin.school_name_ar') }} *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.school_name_en') }}</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.city') }}</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.country') }}</label>
                    <input type="text" name="country" value="{{ old('country', 'Syria') }}" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('admin.school_description') }}</label>
                    <textarea name="description" rows="3" class="input resize-none">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="label">{{ __('admin.website') }}</label>
                    <input type="url" name="website" value="{{ old('website') }}" class="input" placeholder="https://...">
                </div>
                <div>
                    <label class="label">{{ __('admin.logo') }}</label>
                    <input type="file" name="logo" accept="image/*" class="input !py-2.5">
                </div>
            </div>
        </div>

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">💎</span>
                <h3 class="font-bold text-main">{{ __('admin.subscription_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('admin.subscription_plan') }} *</label>
                    <select name="subscription_plan" required class="input">
                        <option value="basic">{{ __('admin.basic') }}</option>
                        <option value="premium">{{ __('admin.premium') }}</option>
                        <option value="enterprise">{{ __('admin.enterprise') }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('admin.subscription_expires') }}</label>
                    <input type="date" name="subscription_expires_at" value="{{ old('subscription_expires_at') }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.max_students') }}</label>
                    <input type="number" name="max_students" value="{{ old('max_students', 500) }}" min="1" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.max_teachers') }}</label>
                    <input type="number" name="max_teachers" value="{{ old('max_teachers', 50) }}" min="1" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.account_status') }}</label>
                    <select name="status" class="input">
                        <option value="active">{{ __('status.active') }}</option>
                        <option value="inactive">{{ __('status.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.schools.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 {{ __('admin.save_school') }}</span>
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