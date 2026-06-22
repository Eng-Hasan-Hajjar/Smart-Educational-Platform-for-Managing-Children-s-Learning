
<?php $__env->startSection('title', __('app.units')); ?>
<?php $__env->startSection('page-title', __('app.units')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($units->total()); ?> <?php echo e(__('teacher.units_count')); ?></p>
        <a href="<?php echo e(route('teacher.units.create')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('teacher.new_unit')); ?>

        </a>
    </div>

    
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="subject_id" class="input sm:w-56">
                <option value=""><?php echo e(__('app.all_subjects')); ?></option>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>" <?php echo e(request('subject_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn-outline"><?php echo e(__('app.filter')); ?></button>
            <?php if(request('subject_id')): ?>
            <a href="<?php echo e(route('teacher.units.index')); ?>" class="btn-outline text-danger-600">✕</a>
            <?php endif; ?>
        </form>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card card-hover animate-fade-up" style="animation-delay:<?php echo e(.06 + $loop->index * .04); ?>s">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        <?php echo e($unit->is_published ? 'bg-success-50 text-success-600' : 'bg-hover text-faint'); ?>">
                📚
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="badge-<?php echo e($unit->is_published ? 'green' : 'yellow'); ?>">
                        <?php echo e($unit->is_published ? __('status.published') : __('status.draft')); ?>

                    </span>
                    <span class="text-xs text-muted"><?php echo e($unit->subject->name ?? ''); ?></span>
                </div>
                <a href="<?php echo e(route('teacher.units.edit', $unit)); ?>" class="font-bold text-main hover:text-brand-500 transition text-base">
                    <?php echo e($unit->name); ?>

                </a>
                <?php if($unit->description): ?>
                <p class="text-muted text-xs mt-1 line-clamp-2"><?php echo e($unit->description); ?></p>
                <?php endif; ?>
                <div class="flex items-center gap-3 mt-2 text-xs text-faint">
                    <span>📖 <?php echo e($unit->lessons_count); ?> <?php echo e(__('teacher.lessons_in_unit')); ?></span>
                    <span>📊 <?php echo e(__('app.order')); ?>: <?php echo e($unit->order); ?></span>
                </div>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="<?php echo e(route('teacher.units.edit', $unit)); ?>" class="btn-outline !py-2 !px-3 text-xs">✏️ <?php echo e(__('app.edit')); ?></a>
                <form method="POST" action="<?php echo e(route('teacher.units.destroy', $unit)); ?>" onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 transition">🗑️</button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📚</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.no_units_yet')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.no_units_hint')); ?></p>
        <a href="<?php echo e(route('teacher.units.create')); ?>" class="btn-primary mt-5 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('teacher.create_first_unit')); ?>

        </a>
    </div>
    <?php endif; ?>

    <?php if($units->hasPages()): ?>
    <div class="flex justify-center"><?php echo e($units->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/units/index.blade.php ENDPATH**/ ?>