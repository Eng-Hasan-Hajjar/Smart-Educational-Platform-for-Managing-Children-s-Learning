<div class="flex items-center justify-between px-4 py-3.5 border-b border-bd">
    <span class="font-bold text-sm text-main flex items-center gap-2">
        🔔 <?php echo e(__('app.notifications')); ?>

    </span>
    <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
        <?php echo csrf_field(); ?>
        <button class="text-xs text-brand-500 hover:text-brand-700 font-semibold transition">
            <?php echo e(__('app.mark_all_read')); ?>

        </button>
    </form>
</div>

<div class="max-h-80 overflow-y-auto divide-y divide-bd">
    <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications()->latest()->take(8)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="px-4 py-3 hover:bg-hover transition flex items-start gap-3 <?php echo e($n->read_at ? '' : 'bg-brand-50/60'); ?>">
        <span class="text-lg flex-shrink-0 mt-0.5">
            <?php if(!$n->read_at): ?>
                <span class="relative flex w-2 h-2">
                    <span class="absolute inline-flex w-full h-full rounded-full bg-brand-500 opacity-75 animate-pulse-glow"></span>
                    <span class="relative inline-flex rounded-full w-2 h-2 bg-brand-500"></span>
                </span>
            <?php else: ?>
                <span class="inline-flex w-2 h-2 rounded-full bg-bds"></span>
            <?php endif; ?>
        </span>
        <div class="flex-1 min-w-0">
            <p class="text-sm text-main leading-relaxed"><?php echo e($n->data['message'] ?? __('app.new_notification')); ?></p>
            <p class="text-xs text-faint mt-1"><?php echo e($n->created_at->diffForHumans()); ?></p>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="px-4 py-10 text-center">
        <p class="text-3xl mb-2">🔔</p>
        <p class="text-muted text-sm"><?php echo e(__('app.no_notifications')); ?></p>
    </div>
    <?php endif; ?>
</div>

<div class="p-3 border-t border-bd">
    <a href="<?php echo e(route('notifications.index')); ?>" class="block text-center text-xs font-bold text-brand-500 hover:text-brand-700 transition py-1">
        <?php echo e(__('app.view_all')); ?> →
    </a>
</div><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/components/notifications-dropdown.blade.php ENDPATH**/ ?>