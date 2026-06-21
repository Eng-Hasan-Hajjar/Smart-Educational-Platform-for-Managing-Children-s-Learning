@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $school->name)
@section('page-title', __('app.edit') . ' — ' . $school->name)
@section('page-subtitle', __('admin.edit_school_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('admin.schools.update', $school) }}" enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf @method('PUT')

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">🏫</span>
                <h3 class="font-bold text-main">{{ __('admin.school_info') }}</h3>
            </div>

            <div class="flex items-center gap-4">
                <img src="{{ $school->logo_url }}" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-bd flex-shrink-0" alt="">
                <div class="flex-1">
                    <label class="label">{{ __('admin.change_logo') }}</label>
                    <input type="file" name="logo" accept="image/*" class="input !py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('admin.school_name_ar') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $school->name) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.school_name_en') }}</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $school->name_en) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $school->email) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $school->phone) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.city') }}</label>
                    <input type="text" name="city" value="{{ old('city', $school->city) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.country') }}</label>
                    <input type="text" name="country" value="{{ old('country', $school->country) }}" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('admin.school_description') }}</label>
                    <textarea name="description" rows="3" class="input resize-none">{{ old('description', $school->description) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('admin.website') }}</label>
                    <input type="url" name="website" value="{{ old('website', $school->website) }}" class="input">
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
                        @foreach(['basic','premium','enterprise'] as $plan)
                        <option value="{{ $plan }}" {{ $school->subscription_plan === $plan ? 'selected' : '' }}>
                            {{ __('admin.'.$plan) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('admin.subscription_expires') }}</label>
                    <input type="date" name="subscription_expires_at"
                           value="{{ old('subscription_expires_at', $school->subscription_expires_at?->format('Y-m-d')) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.max_students') }}</label>
                    <input type="number" name="max_students" value="{{ old('max_students', $school->max_students) }}" min="1" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.max_teachers') }}</label>
                    <input type="number" name="max_teachers" value="{{ old('max_teachers', $school->max_teachers) }}" min="1" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('admin.account_status') }}</label>
                    <select name="status" class="input">
                        @foreach(['active','inactive','suspended'] as $st)
                        <option value="{{ $st }}" {{ $school->status === $st ? 'selected' : '' }}>{{ __('status.'.$st) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-3">
                <a href="{{ route('admin.schools.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
                <button type="submit" :disabled="loading" class="btn-primary">
                    <span x-show="!loading">💾 {{ __('app.save') }}</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ __('app.loading') }}
                    </span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.schools.destroy', $school) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑️ {{ __('app.delete') }}</button>
            </form>
        </div>
    </form>
</div>
@endsection