
<?php $__env->startSection('title', __('admin.add_user')); ?>
<?php $__env->startSection('page-title', __('admin.add_user')); ?>
<?php $__env->startSection('page-subtitle', __('admin.add_user_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        <?php echo csrf_field(); ?>

        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main"><?php echo e(__('admin.user_info')); ?></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label"><?php echo e(__('schooladmin.full_name')); ?> *</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('app.email')); ?> *</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('app.password')); ?> *</label>
                    <input type="password" name="password" required minlength="8" class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('admin.confirm_password')); ?> *</label>
                    <input type="password" name="password_confirmation" required class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('admin.role')); ?> *</label>
                    <select name="role" required class="input">
                        <option value=""><?php echo e(__('app.select_option')); ?></option>
                        <?php $__currentLoopData = ['super_admin'=>__('app.super_admin'),'school_admin'=>__('app.school_admin'),'counselor'=>__('app.counselor'),'teacher'=>__('app.teacher'),'parent'=>__('app.parent'),'student'=>__('app.student')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e(old('role') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="label"><?php echo e(__('admin.school')); ?></label>
                    <select name="school_id" class="input">
                        <option value=""><?php echo e(__('admin.no_school')); ?></option>
                        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school->id); ?>" <?php echo e(old('school_id') == $school->id ? 'selected' : ''); ?>><?php echo e($school->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="label"><?php echo e(__('schooladmin.phone')); ?></label>
                    <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('schooladmin.gender')); ?></label>
                    <select name="gender" class="input">
                        <option value=""><?php echo e(__('app.select_option')); ?></option>
                        <option value="male"><?php echo e(__('schooladmin.male')); ?></option>
                        <option value="female"><?php echo e(__('schooladmin.female')); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn-outline"><?php echo e(__('app.cancel')); ?></a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 <?php echo e(__('admin.create_user')); ?></span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <?php echo e(__('app.loading')); ?>

                </span>
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/admin/users/create.blade.php ENDPATH**/ ?>