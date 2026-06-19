<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - <?php echo e(config('app.name', 'منصة نور التعليمية')); ?></title>

    
    <script src="https://cdn.tailwindcss.com"></script>

    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #8b5cf6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        [dir="rtl"] {
            direction: rtl;
            text-align: right;
        }

        [dir="ltr"] {
            direction: ltr;
            text-align: left;
        }

        .dark {
            color-scheme: dark;
        }

        .light {
            color-scheme: light;
        }

        /* Smooth Transition */
        * {
            @apply transition-colors duration-300;
        }

        /* Input Styles */
        input:focus, textarea:focus, select:focus {
            @apply outline-none ring-2 ring-blue-500;
        }
    </style>

    <?php echo $__env->yieldContent('css'); ?>
</head>
<body class="light" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      :class="{ 'dark': darkMode }"
      @load="$watch('darkMode', val => localStorage.setItem('darkMode', val))">

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
        
        <button @click="darkMode = !darkMode" 
                class="fixed top-6 <?php echo e(app()->getLocale() === 'ar' ? 'left-6' : 'right-6'); ?> p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 z-50">
            <i class="fas" :class="darkMode ? 'fa-sun text-yellow-500' : 'fa-moon text-gray-600'"></i>
        </button>

        
        <div class="fixed top-6 <?php echo e(app()->getLocale() === 'ar' ? 'right-6' : 'left-6'); ?> z-50">
            <a href="<?php echo e(route('set-locale', app()->getLocale() === 'ar' ? 'en' : 'ar')); ?>"
               class="p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 text-sm font-bold text-blue-600 dark:text-blue-400">
                <?php echo e(app()->getLocale() === 'ar' ? 'English' : 'العربية'); ?>

            </a>
        </div>

        
        <div class="w-full max-w-md">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full shadow-lg mb-4">
                    <i class="fas fa-graduation-cap text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    <?php echo e(config('app.name', 'منصة نور التعليمية')); ?>

                </h1>
                <p class="text-gray-600 dark:text-gray-400"><?php echo e(__('auth.login')); ?></p>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-400 font-semibold">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo e(__('validation.failed')); ?>

                    </p>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">• <?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <?php if(session('status')): ?>
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm text-green-700 dark:text-green-400 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php echo e(session('status')); ?>

                    </p>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm text-green-700 dark:text-green-400 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php echo e(session('success')); ?>

                    </p>
                </div>
            <?php endif; ?>

            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8 md:p-10">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            
            <div class="text-center mt-8 text-sm text-gray-600 dark:text-gray-400">
                <p>© <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'منصة نور التعليمية')); ?>. <?php echo e(__('app.all_rights_reserved')); ?></p>
            </div>
        </div>
    </div>

    <?php echo $__env->yieldContent('js'); ?>
</body>
</html><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/layouts/auth.blade.php ENDPATH**/ ?>