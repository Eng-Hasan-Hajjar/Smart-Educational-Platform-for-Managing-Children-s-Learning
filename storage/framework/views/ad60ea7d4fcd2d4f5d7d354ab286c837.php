<?php $__env->startSection('title', __('app.announcements')); ?>
<?php $__env->startSection('page-title', __('app.announcements')); ?>
<?php $__env->startSection('page-subtitle', __('app.announcements_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'super_admin|school_admin')): ?>
    <div class="card animate-fade-up" x-data="{ open: false }">
        <button @click="open = !open" type="button" class="w-full flex items-center justify-between">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">📢</span>
                <?php echo e(__('app.new_announcement')); ?>

            </h3>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-faint transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-cloak x-transition class="mt-5 pt-5 border-t border-bd">
            <form method="POST" action="<?php echo e(route('announcements.store')); ?>" class="space-y-4"
                  x-data="{ loading: false }" @submit="loading = true">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="label"><?php echo e(__('app.title')); ?> *</label>
                    <input type="text" name="title" required class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('app.body')); ?> *</label>
                    <textarea name="body" rows="4" required class="input resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="label"><?php echo e(__('app.type')); ?></label>
                        <select name="type" class="input">
                            <option value="general"><?php echo e(__('app.type_general')); ?></option>
                            <option value="academic"><?php echo e(__('app.type_academic')); ?></option>
                            <option value="urgent"><?php echo e(__('app.type_urgent')); ?></option>
                            <option value="event"><?php echo e(__('app.type_event')); ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="label"><?php echo e(__('app.target_audience')); ?></label>
                        <select name="target_type" class="input">
                            <option value="all"><?php echo e(__('app.all')); ?></option>
                            <option value="teachers"><?php echo e(__('app.teachers')); ?></option>
                            <option value="students"><?php echo e(__('app.students_label')); ?></option>
                            <option value="parents"><?php echo e(__('app.parents_label')); ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="label"><?php echo e(__('app.expires_at')); ?></label>
                        <input type="date" name="expires_at" class="input">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" :disabled="loading" class="btn-primary">📢 <?php echo e(__('app.publish')); ?></button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $isRead = in_array($a->id, $readIds);
        $priorityStyle = match($a->type) {
            'urgent'   => ['border-danger-500/30 bg-danger-50', 'danger', '🚨'],
            'academic' => ['border-warning-500/30 bg-warning-50', 'warning', '📚'],
            'event'    => ['border-info-500/30 bg-info-50', 'info', '📅'],
            default    => ['border-bd', 'brand', '📢'],
        };
    ?>
    <div class="card !p-4 animate-fade-up <?php echo e($priorityStyle[0]); ?> <?php echo e(!$isRead ? 'shadow-glow' : ''); ?>"
         style="animation-delay:<?php echo e(.03 * $loop->index); ?>s"
         <?php if(!$isRead): ?>
         x-data x-init="fetch('<?php echo e(route('announcements.read', $a)); ?>', {method:'POST',headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}})"
         <?php endif; ?>>
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-<?php echo e($priorityStyle[1]); ?>-50 text-<?php echo e($priorityStyle[1]); ?>-600 flex items-center justify-center text-xl flex-shrink-0">
                <?php echo e($priorityStyle[2]); ?>

            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <p class="font-bold text-main"><?php echo e($a->title); ?></p>
                    <?php if(!$isRead): ?>
                    <span class="badge-brand text-[10px]"><?php echo e(__('app.new')); ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-sm text-muted leading-relaxed"><?php echo e($a->body); ?></p>
                <p class="text-xs text-faint mt-2"><?php echo e($a->createdBy->name); ?> · <?php echo e($a->created_at->diffForHumans()); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📢</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('app.no_announcements')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('app.no_announcements_hint')); ?></p>
    </div>
    <?php endif; ?>

    <?php if($announcements->hasPages()): ?>
    <div class="flex justify-center"><?php echo e($announcements->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/shared/announcements/index.blade.php ENDPATH**/ ?>