

<?php $__env->startSection('title', __('auth.register')); ?>

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-6">
    <?php echo csrf_field(); ?>

    
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            <?php echo e(__('app.name')); ?>

        </label>
        <div class="relative">
            <span class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'right-4' : 'left-4'); ?> top-3.5 text-gray-400">
                <i class="fas fa-user"></i>
            </span>
            <input type="text" 
                   id="name" 
                   name="name" 
                   value="<?php echo e(old('name')); ?>"
                   class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="<?php echo e(__('app.name')); ?>"
                   required
                   autofocus>
        </div>
        <?php $__errorArgs = ['name'];
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
                   required>
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
        <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            <?php echo e(__('app.phone')); ?>

        </label>
        <div class="relative">
            <span class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'right-4' : 'left-4'); ?> top-3.5 text-gray-400">
                <i class="fas fa-phone"></i>
            </span>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   value="<?php echo e(old('phone')); ?>"
                   class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="<?php echo e(__('app.phone')); ?>">
        </div>
        <?php $__errorArgs = ['phone'];
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
        <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            <?php echo e(__('app.role')); ?>

        </label>
        <div class="relative">
            <span class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'right-4' : 'left-4'); ?> top-3.5 text-gray-400">
                <i class="fas fa-user-tag"></i>
            </span>
            <select id="role" 
                    name="role"
                    class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white"
                    required>
                <option value=""><?php echo e(__('app.select_role')); ?></option>
                <option value="teacher" <?php echo e(old('role') === 'teacher' ? 'selected' : ''); ?>><?php echo e(__('status.teacher')); ?></option>
                <option value="parent" <?php echo e(old('role') === 'parent' ? 'selected' : ''); ?>><?php echo e(__('status.parent')); ?></option>
                <option value="student" <?php echo e(old('role') === 'student' ? 'selected' : ''); ?>><?php echo e(__('status.student')); ?></option>
            </select>
        </div>
        <?php $__errorArgs = ['role'];
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
                   required>
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

    
    <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
            <?php echo e(__('auth.confirm_password')); ?>

        </label>
        <div class="relative">
            <span class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'right-4' : 'left-4'); ?> top-3.5 text-gray-400">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation"
                   class="w-full <?php echo e(app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4'); ?> py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                   placeholder="<?php echo e(__('auth.confirm_password')); ?>"
                   required>
        </div>
    </div>

    
    <button type="submit" 
            class="w-full py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold rounded-lg transition duration-300 transform hover:scale-105 active:scale-95 shadow-lg">
        <i class="fas fa-user-plus <?php echo e(app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2'); ?>"></i>
        <?php echo e(__('auth.register')); ?>

    </button>

    
    <p class="text-center text-gray-600 dark:text-gray-400">
        <?php echo e(__('auth.have_account')); ?>

        <a href="<?php echo e(route('login')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">
            <?php echo e(__('auth.login')); ?>

        </a>
    </p>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/auth/register.blade.php ENDPATH**/ ?>