@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $user->name)
@section('page-title', __('app.edit') . ' — ' . $user->name)
@section('page-subtitle', __('admin.edit_user_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf @method('PUT')

        <div class="card space-y-5">
            <div class="flex items-center gap-4 pb-3 border-b border-bd">
                <img src="{{ $user->avatar_url }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-bd" alt="">
                <div>
                    <h3 class="font-bold text-main">{{ $user->name }}</h3>
                    <p class="text-xs text-muted">{{ $user->email }}</p>
                </div>
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
                <div>
                    <label class="label">{{ __('admin.new_password_optional') }}</label>
                    <input type="password" name="password" minlength="8" class="input" placeholder="{{ __('admin.leave_empty_no_change') }}">
                </div>
                <div>
                    <label class="label">{{ __('admin.confirm_password') }}</label>
                    <input type="password" name="password_confirmation" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.role') }} *</label>
                    <select name="role" required class="input">
                        @foreach(['super_admin'=>__('app.super_admin'),'school_admin'=>__('app.school_admin'),'counselor'=>__('app.counselor'),'teacher'=>__('app.teacher'),'parent'=>__('app.parent'),'student'=>__('app.student')] as $v=>$l)
                        <option value="{{ $v }}" {{ $user->roles->first()?->name === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('admin.school') }}</label>
                    <select name="school_id" class="input">
                        <option value="">{{ __('admin.no_school') }}</option>
                        @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $user->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('admin.account_status') }}</label>
                    <select name="status" class="input">
                        @foreach(['active'=>__('status.active'),'inactive'=>__('status.inactive'),'banned'=>__('admin.banned')] as $v=>$l)
                        <option value="{{ $v }}" {{ $user->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
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
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑️ {{ __('app.delete') }}</button>
            </form>
            @endif
        </div>
    </form>
</div>
@endsection