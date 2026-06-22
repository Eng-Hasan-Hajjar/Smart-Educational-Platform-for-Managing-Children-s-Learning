<?php $__env->startSection('title', __('app.lessons')); ?>
<?php $__env->startSection('page-title', __('app.lessons')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.lessons_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($lessons->total()); ?> <?php echo e(__('teacher.lesson')); ?></p>
        <a href="<?php echo e(route('teacher.lessons.create')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <?php echo e(__('teacher.add_lesson')); ?>

        </a>
    </div>

    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="input ps-10" placeholder="<?php echo e(__('teacher.search_lessons')); ?>">
            </div>
            <select name="status" class="input sm:w-44">
                <option value=""><?php echo e(__('app.all')); ?></option>
                <option value="published" <?php echo e(request('status')==='published'?'selected':''); ?>><?php echo e(__('status.published')); ?></option>
                <option value="draft" <?php echo e(request('status')==='draft'?'selected':''); ?>><?php echo e(__('status.draft')); ?></option>
            </select>
            <button type="submit" class="btn-outline">🔍 <?php echo e(__('app.filter')); ?></button>
        </form>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card card-hover animate-fade-up" style="animation-delay:<?php echo e(.04 + $loop->index * .03); ?>s">
        <div class="flex items-center gap-4">
            <img src="<?php echo e($lesson->thumbnail_url); ?>" class="w-16 h-16 rounded-2xl object-cover flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge-<?php echo e($lesson->status === 'published' ? 'green' : 'gray'); ?>"><?php echo e(__('status.'.$lesson->status)); ?></span>
                    <?php if($lesson->is_free): ?><span class="badge-info"><?php echo e(__('teacher.free')); ?></span><?php endif; ?>
                </div>
                <p class="font-bold text-main truncate"><?php echo e($lesson->title); ?></p>
                <p class="text-xs text-muted"><?php echo e($lesson->unit?->subject?->name ?? '—'); ?> / <?php echo e($lesson->unit?->title ?? '—'); ?> · ⏱ <?php echo e($lesson->duration_minutes); ?> <?php echo e(__('student.min')); ?> · 👁 <?php echo e($lesson->view_count); ?></p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="<?php echo e(route('teacher.lessons.edit', $lesson)); ?>" class="btn-outline !py-2 text-xs">✏️ <?php echo e(__('app.edit')); ?></a>
                <form method="POST" action="<?php echo e(route('teacher.lessons.publish', $lesson)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button class="btn-outline !py-2 text-xs"><?php echo e($lesson->status === 'published' ? '📥' : '📤'); ?></button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📚</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.no_lessons_yet')); ?></p>
        <a href="<?php echo e(route('teacher.lessons.create')); ?>" class="btn-primary mt-5 inline-flex"><?php echo e(__('teacher.add_lesson')); ?></a>
    </div>
    <?php endif; ?>

    <?php if($lessons->hasPages()): ?><div class="flex justify-center"><?php echo e($lessons->links()); ?></div><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/lessons/index.blade.php ENDPATH**/ ?>