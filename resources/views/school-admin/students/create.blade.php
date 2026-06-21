@extends('layouts.app')
@section('title', __('schooladmin.enroll_student'))
@section('page-title', __('schooladmin.enroll_student'))
@section('page-subtitle', __('schooladmin.enroll_student_subtitle'))

@section('content')
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="{{ route('school-admin.students.store') }}"
          enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        @csrf

        {{-- ══════════ Personal Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.personal_info') }}</h3>
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
                    <label class="label">{{ __('schooladmin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.gender') }}</label>
                    <select name="gender" class="input">
                        <option value="male">{{ __('schooladmin.male') }}</option>
                        <option value="female">{{ __('schooladmin.female') }}</option>
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.birth_date') }}</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label">{{ __('schooladmin.avatar') }}</label>
                    <div class="relative border-2 border-dashed border-bd rounded-2xl p-5 text-center hover:border-brand-400 transition cursor-pointer"
                         x-data="{ preview: null }">
                        <input type="file" name="avatar" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer"
                               @change="if($event.target.files[0]) preview = URL.createObjectURL($event.target.files[0])">
                        <div x-show="!preview" class="pointer-events-none">
                            <span class="text-3xl">🖼️</span>
                            <p class="text-muted text-sm mt-1">{{ __('teacher.drag_drop_image') }}</p>
                        </div>
                        <img x-show="preview" :src="preview" x-cloak class="max-h-32 mx-auto rounded-xl object-contain pointer-events-none" alt="">
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ Academic Info ══════════ --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">🎓</span>
                <h3 class="font-bold text-main">{{ __('schooladmin.academic_info') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">{{ __('schooladmin.academic_level') }} *</label>
                    <select name="academic_level_id" required class="input" id="levelSelect">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($levels as $level)
                        <option value="{{ $level->id }}" {{ old('academic_level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('app.classrooms') }}</label>
                    <select name="classroom_id" class="input">
                        <option value="">{{ __('schooladmin.no_classroom_yet') }}</option>
                        @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" data-level="{{ $c->academic_level_id }}"
                                {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} — {{ $c->academicLevel->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.student_number') }}</label>
                    <input type="text" name="student_number" value="{{ old('student_number') }}"
                           class="input" placeholder="{{ __('schooladmin.auto_generated_if_empty') }}">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.enrollment_date') }}</label>
                    <input type="date" name="enrollment_date" value="{{ old('enrollment_date', today()->toDateString()) }}" class="input">
                </div>
                <div>
                    <label class="label">{{ __('schooladmin.blood_type') }}</label>
                    <select name="blood_type" class="input">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                        <option value="{{ $bt }}" {{ old('blood_type') === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ══════════ Actions ══════════ --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('school-admin.students.index') }}" class="btn-outline">{{ __('app.cancel') }}</a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 {{ __('schooladmin.save_student') }}</span>
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

@push('scripts')
<script>
    // فلترة الفصول حسب المرحلة المختارة
    document.getElementById('levelSelect')?.addEventListener('change', function () {
        const levelId = this.value;
        document.querySelectorAll('select[name="classroom_id"] option[data-level]').forEach(opt => {
            opt.hidden = levelId && opt.dataset.level !== levelId;
        });
    });
</script>
@endpush