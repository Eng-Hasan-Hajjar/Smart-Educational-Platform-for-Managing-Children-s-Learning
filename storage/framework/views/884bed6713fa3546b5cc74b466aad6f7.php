
<?php $__env->startSection('title', __('app.new_message')); ?>
<?php $__env->startSection('page-title', __('app.new_message')); ?>
<?php $__env->startSection('page-subtitle', __('app.compose_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="<?php echo e(route('messages.store')); ?>" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        <?php echo csrf_field(); ?>

        <div class="card space-y-5">
            <div>
                <label class="label"><?php echo e(__('app.recipient')); ?> *</label>
                <select name="recipient_id" required class="input">
                    <option value=""><?php echo e(__('app.select_option')); ?></option>
                    <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($contact->id); ?>" <?php echo e(($recipient?->id ?? old('recipient_id')) == $contact->id ? 'selected' : ''); ?>>
                        <?php echo e($contact->name); ?> — <?php echo e(__('app.'.($contact->roles->first()?->name ?? 'user'))); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="label"><?php echo e(__('app.subject')); ?></label>
                <input type="text" name="subject" value="<?php echo e(old('subject')); ?>" class="input"
                       placeholder="<?php echo e(__('app.subject_placeholder')); ?>">
            </div>
            <div>
                <label class="label"><?php echo e(__('app.message')); ?> *</label>
                <textarea name="body" rows="6" required class="input resize-none"
                          placeholder="<?php echo e(__('app.message_placeholder')); ?>"><?php echo e(old('body')); ?></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('messages.index')); ?>" class="btn-outline"><?php echo e(__('app.cancel')); ?></a>
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">📤 <?php echo e(__('app.send')); ?></span>
                <span x-show="loading" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <?php echo e(__('app.sending')); ?>

                </span>
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/shared/messages/compose.blade.php ENDPATH**/ ?>