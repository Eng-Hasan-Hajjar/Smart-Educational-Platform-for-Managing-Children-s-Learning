
<?php $__env->startSection('title', __('app.schools')); ?>
<?php $__env->startSection('page-title', __('app.schools')); ?>
<?php $__env->startSection('page-subtitle', __('admin.schools_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($schools->total()); ?> <?php echo e(__('admin.schools_count')); ?></p>
        <a href="<?php echo e(route('admin.schools.create')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('admin.add_school')); ?>

        </a>
    </div>

    
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       class="input ps-10" placeholder="<?php echo e(__('admin.search_schools')); ?>">
            </div>
            <select name="status" class="input sm:w-44">
                <option value=""><?php echo e(__('app.all')); ?></option>
                <option value="active"   <?php echo e(request('status') === 'active'   ? 'selected' : ''); ?>><?php echo e(__('status.active')); ?></option>
                <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>><?php echo e(__('status.inactive')); ?></option>
                <option value="suspended"<?php echo e(request('status') === 'suspended'? 'selected' : ''); ?>><?php echo e(__('status.suspended')); ?></option>
            </select>
            <button type="submit" class="btn-outline">🔍 <?php echo e(__('app.filter')); ?></button>
            <?php if(request()->hasAny(['search','status'])): ?>
            <a href="<?php echo e(route('admin.schools.index')); ?>" class="btn-outline text-danger-600 px-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            <?php endif; ?>
        </form>
    </div>

    
    <?php if($schools->count()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 stagger">
        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card card-hover">
            <div class="flex items-start gap-3 mb-4">
                <img src="<?php echo e($school->logo_url); ?>" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-bd flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-main truncate"><?php echo e($school->name); ?></p>
                    <p class="text-xs text-muted mt-0.5"><?php echo e($school->city ?? '—'); ?>, <?php echo e($school->country ?? '—'); ?></p>
                    <span class="badge-<?php echo e($school->status === 'active' ? 'green' : ($school->status === 'suspended' ? 'red' : 'gray')); ?> mt-1.5">
                        <?php echo e(__('status.'.$school->status)); ?>

                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="text-center p-2.5 rounded-xl bg-brand-50">
                    <p class="font-black text-brand-600"><?php echo e($school->students_count); ?></p>
                    <p class="text-[10px] text-brand-600"><?php echo e(__('admin.students_short')); ?></p>
                </div>
                <div class="text-center p-2.5 rounded-xl bg-info-50">
                    <p class="font-black text-info-600"><?php echo e($school->teachers_count); ?></p>
                    <p class="text-[10px] text-info-600"><?php echo e(__('admin.teachers_short')); ?></p>
                </div>
            </div>

            <div class="flex items-center justify-between mb-4">
                <span class="badge-brand"><?php echo e(__('admin.'.$school->subscription_plan)); ?></span>
                <?php if($school->subscription_expires_at): ?>
                <span class="text-xs text-faint">
                    <?php echo e(__('admin.expires')); ?>: <?php echo e($school->subscription_expires_at->format('d/m/Y')); ?>

                </span>
                <?php endif; ?>
            </div>

            <div class="flex gap-2">
                <a href="<?php echo e(route('admin.schools.edit', $school)); ?>" class="flex-1 btn-outline text-xs text-center justify-center">
                    ✏️ <?php echo e(__('app.edit')); ?>

                </a>
                <form method="POST" action="<?php echo e(route('admin.schools.destroy', $school)); ?>"
                      onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn-danger text-xs">🗑️</button>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="flex justify-center"><?php echo e($schools->withQueryString()->links()); ?></div>
    <?php else: ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">🏫</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('admin.no_schools_yet')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('admin.no_schools_hint')); ?></p>
        <a href="<?php echo e(route('admin.schools.create')); ?>" class="btn-primary mt-5 inline-flex">
            <?php echo e(__('admin.add_first_school')); ?>

        </a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/admin/schools/index.blade.php ENDPATH**/ ?>