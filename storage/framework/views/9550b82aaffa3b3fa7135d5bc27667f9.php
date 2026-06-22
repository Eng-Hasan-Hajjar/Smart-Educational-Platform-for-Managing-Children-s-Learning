<?php
    // تحديد الـ Theme: من حساب المستخدم، أو من الكوكي، أو افتراضي 'light'
    $__theme = auth()->check()
        ? (auth()->user()->theme ?? 'light')
        : request()->cookie('theme', 'light');

    $__locale = app()->getLocale();
    $__dir    = $__locale === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?php echo e($__locale); ?>" dir="<?php echo e($__dir); ?>" data-theme="<?php echo e($__theme); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', __('app.dashboard')); ?> | <?php echo e(__('app.platform_name')); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo e(asset('css/nour-theme.css')); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['selector', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        arabic: ['Tajawal', 'sans-serif'],
                        latin:  ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        app:     'var(--bg-app)',
                        surface: 'var(--bg-surface)',
                        surface2:'var(--bg-surface-2)',
                        hover:   'var(--bg-hover)',
                        bd:      'var(--border-app)',
                        bds:     'var(--border-strong)',
                        main:    'var(--text-main)',
                        muted:   'var(--text-muted)',
                        faint:   'var(--text-faint)',
                        brand: {
                            50:'var(--brand-50)', 100:'var(--brand-100)', 200:'var(--brand-200)',
                            400:'var(--brand-400)', 500:'var(--brand-500)', 600:'var(--brand-600)', 700:'var(--brand-700)'
                        },
                        accent: { 50:'var(--accent-50)', 400:'var(--accent-400)', 500:'var(--accent-500)', 600:'var(--accent-600)' },
                        success:{ 50:'var(--success-50)', 500:'var(--success-500)', 600:'var(--success-600)' },
                        danger: { 50:'var(--danger-50)', 500:'var(--danger-500)', 600:'var(--danger-600)' },
                        warning:{ 50:'var(--warning-50)', 500:'var(--warning-500)', 600:'var(--warning-600)' },
                        info:   { 50:'var(--info-50)', 500:'var(--info-500)', 600:'var(--info-600)' },
                    },
                    boxShadow: {
                        glow: 'var(--shadow-glow)',
                        'glow-strong': 'var(--shadow-glow-strong)',
                    },
                    borderRadius: { '2xl': '1rem', '3xl': '1.5rem' },
                }
            }
        }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="<?php echo e($__locale === 'ar' ? 'font-arabic' : 'font-latin'); ?> bg-app text-main antialiased">

<div class="flex min-h-screen" x-data="{ sidebarOpen: true, mobileOpen: false }">

    
    <div x-show="mobileOpen" x-cloak @click="mobileOpen=false"
         class="sidebar-overlay fixed inset-0 z-40 lg:hidden animate-fade"></div>

    
    <aside
        :class="[
            sidebarOpen ? 'w-72' : 'w-0 lg:w-20',
            mobileOpen ? 'translate-x-0' : (
                '<?php echo e($__dir); ?>' === 'rtl' ? 'translate-x-full lg:translate-x-0' : '-translate-x-full lg:translate-x-0'
            )
        ]"
        class="sidebar-shell fixed lg:sticky top-0 z-50 h-screen flex-shrink-0 flex flex-col
               transition-all duration-300 ease-[cubic-bezier(.22,1,.36,1)] overflow-hidden
               <?php echo e($__dir === 'rtl' ? 'right-0' : 'left-0'); ?>">

        
        <div class="flex items-center gap-3 px-5 py-5 flex-shrink-0">
            <div class="logo-orb">🎓</div>
            <div x-show="sidebarOpen" x-cloak class="animate-fade overflow-hidden whitespace-nowrap">
                <p class="text-white font-extrabold text-sm leading-tight"><?php echo e(__('app.platform_name')); ?></p>
                <p class="text-white/45 text-[11px]"><?php echo e($__locale === 'ar' ? 'Nour Platform' : 'منصة نور'); ?></p>
            </div>
        </div>

        <div class="divider-glow mx-5"></div>

        
        <div class="px-4 py-4 flex-shrink-0">
            <div class="flex items-center gap-3 <?php echo e(true ? '' : ''); ?>">
                <div class="avatar-ring flex-shrink-0">
                    <img src="<?php echo e(auth()->user()->avatar_url); ?>" class="w-10 h-10 object-cover" alt="">
                </div>
                <div x-show="sidebarOpen" x-cloak class="min-w-0 animate-fade">
                    <p class="text-white text-sm font-bold truncate"><?php echo e(auth()->user()->name); ?></p>
                    <p class="text-white/45 text-[11px] truncate"><?php echo e(__('app.' . (auth()->user()->roles->first()?->name ?? 'student'))); ?></p>
                </div>
            </div>
        </div>

        
        <nav class="flex-1 px-4 pb-4 overflow-y-auto space-y-1.5">
            <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </nav>

        
        <div class="px-4 py-4 flex-shrink-0 space-y-1.5">
            <div class="divider-glow mb-2"></div>
            <a href="<?php echo e(route('profile.edit')); ?>" class="nav-link <?php echo e(request()->routeIs('profile.*') ? 'active' : ''); ?>">
                <span class="nav-icon">⚙️</span>
                <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.profile')); ?></span>
            </a>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="nav-link w-full">
                    <span class="nav-icon">🚪</span>
                    <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.logout')); ?></span>
                </button>
            </form>
        </div>
    </aside>

    
    <div class="flex-1 flex flex-col min-w-0">

        
        <header class="bg-surface/80 backdrop-blur-md border-b border-bd px-4 sm:px-6 py-3.5
                        flex items-center justify-between sticky top-0 z-30">

            <div class="flex items-center gap-3">
                
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden theme-toggle">
                    <svg class="!relative !opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex theme-toggle">
                    <svg class="!relative !opacity-100 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </button>

                <div>
                    <h1 class="font-extrabold text-main text-base sm:text-lg leading-tight"><?php echo $__env->yieldContent('page-title', __('app.dashboard')); ?></h1>
                    <?php if (! empty(trim($__env->yieldContent('page-subtitle')))): ?>
                    <p class="text-muted text-xs mt-0.5"><?php echo $__env->yieldContent('page-subtitle'); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-center gap-2">

                
                <?php echo $__env->make('components.language-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <?php echo $__env->make('components.theme-toggle', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <div x-data="{ open:false, count:0 }" x-init="fetch('<?php echo e(route('notifications.count')); ?>').then(r=>r.json()).then(d=>count=d.count)" class="relative">
                    <button @click="open=!open" class="theme-toggle relative">
                        <svg class="!relative !opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="count>0" x-text="count" x-cloak class="notif-dot"></span>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open=false"
                         x-transition:enter="animate-scale-in"
                         class="dropdown-panel absolute <?php echo e($__dir === 'rtl' ? 'left-0' : 'right-0'); ?> mt-2 w-80 z-50 overflow-hidden origin-top-<?php echo e($__dir === 'rtl' ? 'left' : 'right'); ?>">
                        <?php echo $__env->make('components.notifications-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                
                <a href="<?php echo e(route('messages.index')); ?>" class="theme-toggle">
                    <svg class="!relative !opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
            </div>
        </header>

        
        <div class="px-4 sm:px-6 pt-4 space-y-2">
            <?php if(session('success')): ?>
            <div class="card !p-3.5 flex items-center gap-3 border-success-500/30 bg-success-50 text-success-600 animate-fade-up">
                <span class="text-lg">✅</span>
                <span class="text-sm font-medium"><?php echo e(session('success')); ?></span>
            </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
            <div class="card !p-3.5 flex items-center gap-3 border-danger-500/30 bg-danger-50 text-danger-600 animate-fade-up">
                <span class="text-lg">❌</span>
                <span class="text-sm font-medium"><?php echo e(session('error')); ?></span>
            </div>
            <?php endif; ?>
            <?php if(session('info')): ?>
            <div class="card !p-3.5 flex items-center gap-3 border-info-500/30 bg-info-50 text-info-600 animate-fade-up">
                <span class="text-lg">ℹ️</span>
                <span class="text-sm font-medium"><?php echo e(session('info')); ?></span>
            </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
            <div class="card !p-3.5 bg-danger-50 border-danger-500/30 animate-fade-up">
                <ul class="list-disc list-inside text-danger-600 text-sm space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        
        <main class="flex-1 px-4 sm:px-6 pb-10 pt-4 animate-fade-up">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <footer class="text-center py-4 text-xs text-faint border-t border-bd">
            © <?php echo e(date('Y')); ?> <?php echo e(__('app.platform_name')); ?> — <?php echo e($__locale === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved'); ?>

        </footer>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/layouts/app.blade.php ENDPATH**/ ?>