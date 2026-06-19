

<?php $__env->startSection('title', __('auth.login')); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
    <?php echo csrf_field(); ?>

    
    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            <?php echo e(__('app.email')); ?>

        </label>
        <div class="relative">
            <span class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'right-4' : 'left-4'); ?> top-3.5 text-gray-400">
                <i class="fas fa-envelope"></i>
            </span>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="<?php echo e(old('email')); ?>"
                   class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="<?php echo e(__('app.email')); ?>"
                   required
                   autocomplete="email">
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-sm text-red-600 dark:text-red-400 mt-2"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            <?php echo e(__('app.password')); ?>

        </label>
        <div class="relative">
            <span class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'right-4' : 'left-4'); ?> top-3.5 text-gray-400">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" 
                   id="password" 
                   name="password"
                   class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="<?php echo e(__('app.password')); ?>"
                   required
                   autocomplete="current-password">
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <p class="text-sm text-red-600 dark:text-red-400 mt-2"><?php echo e($message); ?></p>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="flex <?php echo e(app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row'); ?> items-center justify-between">
        <label for="remember" class="flex <?php echo e(app()->getLocale() === 'ar' ? 'flex-row-reverse' : 'flex-row'); ?> items-center cursor-pointer">
            <input type="checkbox" 
                   id="remember" 
                   name="remember" 
                   <?php echo e(old('remember') ? 'checked' : ''); ?>

                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600">
            <span class="text-sm text-gray-600 dark:text-gray-400 <?php echo e(app()->getLocale() === 'ar' ? 'mr-3' : 'ml-3'); ?>">
                <?php echo e(__('auth.remember_me')); ?>

            </span>
        </label>
        <a href="<?php echo e(route('forgot-password')); ?>" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            <?php echo e(__('auth.forgot_password')); ?>

        </a>
    </div>

    
    <button type="submit" 
            class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 active:scale-95 shadow-lg">
        <i class="fas fa-sign-in-alt <?php echo e(app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2'); ?>"></i>
        <?php echo e(__('auth.login')); ?>

    </button>

    
    <p class="text-center text-gray-600 dark:text-gray-400">
        <?php echo e(__('auth.dont_have_account')); ?>

        <a href="<?php echo e(route('register')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            <?php echo e(__('auth.register')); ?>

        </a>
    </p>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/auth/login.blade.php ENDPATH**/ ?>