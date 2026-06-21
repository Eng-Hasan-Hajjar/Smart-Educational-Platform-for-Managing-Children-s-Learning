@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $teacher->name)
@section('page-title', __('app.edit') . ' — ' . $teacher->name)
@section('page-subtitle', __('schooladmin.edit_teacher_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.teachers.update', $teacher) }}"
          enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf @method('PUT')

        {{-- ══════════ Personal Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.personal_info') }}</h3>
            </div>

            {{-- Avatar Preview --}}
            <div class="flex items-center gap-4">
                <div class="avatar-ring flex-shrink-0">
                    <img src="{{ $teacher->avatar_url }}" class="w-16 h-16 object-cover" alt="">
                </div>
                <div class="flex-1">
                    <label class="label">{{ __('schooladmin.change_avatar') }}</label>
                    <input type="file" name="avatar" accept="image/*" class="input !py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.full_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.account_status') }}</label>
                    <select name="status" class="input">
                        <option value="active"   {{ $teacher->status === 'active'   ? 'selected' : '' }}>{{ __('status.active') }}</option>
                        <option value="inactive" {{ $teacher->status === 'inactive' ? 'selected' : '' }}>{{ __('status.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ══════════ Professional Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">🎓</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.professional_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.specialization') }}</label>
                    <input type="text" name="specialization"
                           value="{{ old('specialization', $teacher->teacherProfile?->specialization) }}"
                           class="input" placeholder="{{ __('schooladmin.specialization_placeholder') }}">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.qualification') }}</label>
                    <input type="text" name="qualification"
                           value="{{ old('qualification', $teacher->teacherProfile?->qualification) }}"
                           class="input" placeholder="{{ __('schooladmin.qualification_placeholder') }}">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('schooladmin.experience_years') }}</label>
                    <input type="number" name="experience_years"
                           value="{{ old('experience_years', $teacher->teacherProfile?->experience_years ?? 0) }}"
                           min="0" class="input">
                </div>
            </div>

            <div>
                <label class="label">{{ __('schooladmin.bio') }}</label>
                <textarea name="bio" rows="3" class="input resize-none"
                          placeholder="{{ __('schooladmin.bio_placeholder') }}">{{ old('bio', $teacher->teacherProfile?->bio) }}</textarea>
            </div>
        </div>

        {{-- ══════════ Actions ══════════ --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-3">
                <a href="{{ route('school-admin.teachers.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
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
            <form method="POST" action="{{ route('school-admin.teachers.destroy', $teacher) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑️ {{ __('app.delete') }}</button>
            </form>
        </div>
    </form>
</div>
@endsection