@extends('layouts.app')
@section('title', __('app.profile'))
@section('page-title', __('app.profile'))
@section('page-subtitle', __('app.profile_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false, preview: '{{ $user->avatar_url }}' }" @submit="loading = true">
        @csrf @method('PUT')

        {{-- ══════════ Avatar ══════════ --}}
        <div class="card text-center">
            <div class="relative inline-block">
                <img :src="preview" class="w-24 h-24 rounded-full object-cover ring-4 ring-brand-100 mx-auto" alt="">
                <label class="absolute bottom-0 end-0 w-8 h-8 rounded-full bg-brand-500 text-white flex items-center justify-center cursor-pointer hover:bg-brand-600 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 100 6 3 3 0 000-6z"/>
                    </svg>
                    <input type="file" name="avatar" accept="image/*" class="hidden"
                           @change="if($event.target.files[0]) preview = URL.createObjectURL($event.target.files[0])">
                </label>
            </div>
            <p class="font-bold text-main mt-3">{{ $user->name }}</p>
            <p class="text-xs text-muted">{{ __('app.'.($user->roles->first()?->name ?? 'user')) }}</p>
        </div>

        {{-- ══════════ Basic Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main">{{ __('app.basic_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.full_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('schooladmin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input">
                </div>
            </div>
        </div>

        {{-- ══════════ Change Password ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">🔒</span>
                <h3 class="font-bold text-main">{{ __('app.change_password') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('app.current_password') }}</label>
                    <input type="password" name="current_password" class="input">
                </div>
                <div></div>
                <div>
                    <label class="label">{{ __('app.new_password') }}</label>
                    <input type="password" name="new_password" minlength="8" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.confirm_password') }}</label>
                    <input type="password" name="new_password_confirmation" class="input">
                </div>
            </div>
            <p class="text-xs text-faint">{{ __('app.password_change_hint') }}</p>
        </div>

        {{-- ══════════ Preferences ══════════ --}}
        <div class="card space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">⚙️</span>
                <h3 class="font-bold text-main">{{ __('app.preferences') }}</h3>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-main">{{ __('app.dark_mode') }}</p>
                    <p class="text-xs text-muted">{{ __('app.dark_mode_hint') }}</p>
                </div>
                <x-theme-toggle />
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-bd">
                <div>
                    <p class="text-sm font-bold text-main">{{ __('app.language') }}</p>
                    <p class="text-xs text-muted">{{ __('app.language_hint') }}</p>
                </div>
                <x-language-switcher />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 {{ __('app.save_changes') }}</span>
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