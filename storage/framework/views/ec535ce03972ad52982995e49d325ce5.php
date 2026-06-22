
<?php $__env->startSection('title', __('app.profile')); ?>
<?php $__env->startSection('page-title', __('app.profile')); ?>
<?php $__env->startSection('page-subtitle', __('app.profile_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto animate-fade-up">
    <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="space-y-6"
          x-data="{ loading: false, preview: '<?php echo e($user->avatar_url); ?>' }" @submit="loading = true">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

        
        <div class="card text-center">
            <div class="relative inline-block">
                <img :src="preview" class="w-24 h-24 rounded-full object-cover ring-4 ring-brand-100 mx-auto" alt="">
                <label class="absolute bottom-0 end-0 w-8 h-8 rounded-full bg-brand-500 text-white flex items-center justify-center cursor-pointer hover:bg-brand-600 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13a3 3 0 100 6 3 3 0 000-6z"/>
                    </svg>
                    <input type="file" name="avatar" accept="image/*" class="hidden"
                           @change="if($event.target.files[0]) preview = URL.createObjectURL($event.target.files[0])">
                </label>
            </div>
            <p class="font-bold text-main mt-3"><?php echo e($user->name); ?></p>
            <p class="text-xs text-muted"><?php echo e(__('app.'.($user->roles->first()?->name ?? 'user'))); ?></p>
        </div>

        
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">👤</span>
                <h3 class="font-bold text-main"><?php echo e(__('app.basic_info')); ?></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label"><?php echo e(__('schooladmin.full_name')); ?> *</label>
                    <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('app.email')); ?> *</label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required class="input">
                </div>
                <div class="md:col-span-2">
                    <label class="label"><?php echo e(__('schooladmin.phone')); ?></label>
                    <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="input">
                </div>
            </div>
        </div>

        
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">🔒</span>
                <h3 class="font-bold text-main"><?php echo e(__('app.change_password')); ?></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label"><?php echo e(__('app.current_password')); ?></label>
                    <input type="password" name="current_password" class="input">
                </div>
                <div></div>
                <div>
                    <label class="label"><?php echo e(__('app.new_password')); ?></label>
                    <input type="password" name="new_password" minlength="8" class="input">
                </div>
                <div>
                    <label class="label"><?php echo e(__('admin.confirm_password')); ?></label>
                    <input type="password" name="new_password_confirmation" class="input">
                </div>
            </div>
            <p class="text-xs text-faint"><?php echo e(__('app.password_change_hint')); ?></p>
        </div>

        
        <div class="card space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center text-base">⚙️</span>
                <h3 class="font-bold text-main"><?php echo e(__('app.preferences')); ?></h3>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-main"><?php echo e(__('app.dark_mode')); ?></p>
                    <p class="text-xs text-muted"><?php echo e(__('app.dark_mode_hint')); ?></p>
                </div>
                <?php if (isset($component)) { $__componentOriginal2090438866f3dcdb76cd8b070bcc302d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $attributes = $__attributesOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__attributesOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d)): ?>
<?php $component = $__componentOriginal2090438866f3dcdb76cd8b070bcc302d; ?>
<?php unset($__componentOriginal2090438866f3dcdb76cd8b070bcc302d); ?>
<?php endif; ?>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-bd">
                <div>
                    <p class="text-sm font-bold text-main"><?php echo e(__('app.language')); ?></p>
                    <p class="text-xs text-muted"><?php echo e(__('app.language_hint')); ?></p>
                </div>
                <?php if (isset($component)) { $__componentOriginal8d3bff7d7383a45350f7495fc470d934 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8d3bff7d7383a45350f7495fc470d934 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.language-switcher','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('language-switcher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8d3bff7d7383a45350f7495fc470d934)): ?>
<?php $attributes = $__attributesOriginal8d3bff7d7383a45350f7495fc470d934; ?>
<?php unset($__attributesOriginal8d3bff7d7383a45350f7495fc470d934); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8d3bff7d7383a45350f7495fc470d934)): ?>
<?php $component = $__componentOriginal8d3bff7d7383a45350f7495fc470d934; ?>
<?php unset($__componentOriginal8d3bff7d7383a45350f7495fc470d934); ?>
<?php endif; ?>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" :disabled="loading" class="btn-primary">
                <span x-show="!loading">💾 <?php echo e(__('app.save_changes')); ?></span>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/shared/profile/edit.blade.php ENDPATH**/ ?>