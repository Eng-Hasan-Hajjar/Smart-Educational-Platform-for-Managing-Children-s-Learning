@extends('layouts.app')
@section('title', __('app.edit') . ' — ' . $student->name)
@section('page-title', __('app.edit') . ' — ' . $student->name)
@section('page-subtitle', __('schooladmin.edit_student_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.students.update', $student) }}"
          enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf @method('PUT')

        {{-- ══════════ Personal Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.personal_info') }}</h3>
            </div>

            <div class="flex items-center gap-4">
                <div class="avatar-ring flex-shrink-0">
                    <img src="{{ $student->avatar_url }}" class="w-16 h-16 object-cover" alt="">
                </div>
                <div class="flex-1">
                    <label class="label">{{ __('schooladmin.change_avatar') }}</label>
                    <input type="file" name="avatar" accept="image/*" class="input !py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.full_name') }} *</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('app.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}" required class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.account_status') }}</label>
                    <select name="status" class="input">
                        <option value="active"   {{ $student->status === 'active'   ? 'selected' : '' }}>{{ __('status.active') }}</option>
                        <option value="inactive" {{ $student->status === 'inactive' ? 'selected' : '' }}>{{ __('status.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- ══════════ Academic Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">🎓</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.academic_info') }}</h3>
            </div>

            <div>
                <label class="label">{{ __('schooladmin.academic_level') }} *</label>
                <select name="academic_level_id" required class="input">
                    @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('academic_level_id', $student->studentProfile?->academic_level_id) == $level->id ? 'selected' : '' }}>
                        {{ $level->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            @if($student->studentProfile)
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div class="bg-surface2 rounded-2xl p-3 text-center">
                    <p class="text-xs text-muted mb-1">{{ __('schooladmin.student_number') }}</p>
                    <p class="font-bold text-main">{{ $student->studentProfile->student_number ?? '—' }}</p>
                </div>
                <div class="bg-surface2 rounded-2xl p-3 text-center">
                    <p class="text-xs text-muted mb-1">{{ __('schooladmin.current_status') }}</p>
                    <span class="badge-{{ $student->studentProfile->status_color }}">{{ $student->studentProfile->status_label }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- ══════════ Actions ══════════ --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex gap-3">
                <a href="{{ route('school-admin.students.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
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
            <form method="POST" action="{{ route('school-admin.students.destroy', $student) }}"
                  onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger">🗑️ {{ __('app.delete') }}</button>
            </form>
        </div>
    </form>
</div>
@endsection