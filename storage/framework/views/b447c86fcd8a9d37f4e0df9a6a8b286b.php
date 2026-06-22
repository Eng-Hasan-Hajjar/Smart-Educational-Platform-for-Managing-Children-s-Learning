
<?php $__env->startSection('title', __('app.notifications')); ?>
<?php $__env->startSection('page-title', __('app.notifications')); ?>
<?php $__env->startSection('page-subtitle', __('app.notifications_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <?php if($notifications->total()): ?>
    <div class="flex items-center justify-between animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($notifications->total()); ?> <?php echo e(__('app.notification')); ?></p>
        <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
            <?php echo csrf_field(); ?>
            <button class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                ✓ <?php echo e(__('app.mark_all_read')); ?>

            </button>
        </form>
    </div>
    <?php endif; ?>

    
    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card !p-4 animate-fade-up flex items-start gap-3
                <?php echo e(is_null($n->read_at) ? 'border-brand-400/30 bg-brand-50/40' : ''); ?>"
         style="animation-delay:<?php echo e(.02 * $loop->index); ?>s">

        <?php if(is_null($n->read_at)): ?>
        <span class="relative flex w-2.5 h-2.5 mt-1.5 flex-shrink-0">
            <span class="absolute inline-flex w-full h-full rounded-full bg-brand-500 opacity-75 animate-pulse-glow"></span>
            <span class="relative inline-flex rounded-full w-2.5 h-2.5 bg-brand-500"></span>
        </span>
        <?php else: ?>
        <span class="w-2.5 h-2.5 mt-1.5 flex-shrink-0"></span>
        <?php endif; ?>

        <div class="flex-1 min-w-0">
            <p class="text-sm text-main leading-relaxed"><?php echo e($n->data['message'] ?? __('app.new_notification')); ?></p>
            <p class="text-xs text-faint mt-1"><?php echo e($n->created_at->diffForHumans()); ?></p>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <?php if(is_null($n->read_at)): ?>
            <form method="POST" action="<?php echo e(route('notifications.read', $n->id)); ?>">
                <?php echo csrf_field(); ?>
                <button class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                    <?php echo e(__('app.mark_read')); ?>

                </button>
            </form>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('notifications.destroy', $n->id)); ?>"
                  onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="text-faint hover:text-danger-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">🔔</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('app.no_notifications')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('app.no_notifications_hint')); ?></p>
    </div>
    <?php endif; ?>

    <?php if($notifications->hasPages()): ?>
    <div class="flex justify-center"><?php echo e($notifications->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/shared/notifications/index.blade.php ENDPATH**/ ?>