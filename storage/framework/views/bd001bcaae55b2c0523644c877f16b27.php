
<?php $__env->startSection('title', __('app.messages')); ?>
<?php $__env->startSection('page-title', __('app.messages')); ?>
<?php $__env->startSection('page-subtitle', __('app.messages_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    <div class="flex items-center justify-between animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($conversations->total()); ?> <?php echo e(__('app.conversation')); ?></p>
        <a href="<?php echo e(route('messages.compose')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('app.new_message')); ?>

        </a>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php $other = $conv->participants->first(); ?>
    <a href="<?php echo e(route('messages.show', $conv)); ?>"
       class="card card-hover flex items-center gap-3 animate-fade-up <?php echo e($conv->unread_count > 0 ? 'border-brand-400/30 bg-brand-50/30' : ''); ?>"
       style="animation-delay:<?php echo e(.02 * $loop->index); ?>s">

        <img src="<?php echo e($other?->avatar_url); ?>" class="w-12 h-12 rounded-full object-cover flex-shrink-0" alt="">

        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="font-bold text-main truncate"><?php echo e($other?->name ?? __('app.unknown_user')); ?></p>
                <span class="text-xs text-faint flex-shrink-0"><?php echo e($conv->latestMessage?->created_at->diffForHumans()); ?></span>
            </div>
            <p class="text-sm text-muted truncate mt-0.5">
                <?php echo e($conv->latestMessage?->body ?? '—'); ?>

            </p>
        </div>

        <?php if($conv->unread_count > 0): ?>
        <span class="w-6 h-6 rounded-full bg-brand-500 text-white text-xs font-black flex items-center justify-center flex-shrink-0">
            <?php echo e($conv->unread_count); ?>

        </span>
        <?php endif; ?>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">💬</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('app.no_conversations')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('app.no_conversations_hint')); ?></p>
        <a href="<?php echo e(route('messages.compose')); ?>" class="btn-primary mt-5 inline-flex">
            <?php echo e(__('app.start_conversation')); ?>

        </a>
    </div>
    <?php endif; ?>

    <?php if($conversations->hasPages()): ?>
    <div class="flex justify-center"><?php echo e($conversations->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/shared/messages/index.blade.php ENDPATH**/ ?>