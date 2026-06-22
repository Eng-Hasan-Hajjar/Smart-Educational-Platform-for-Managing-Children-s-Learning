<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="theme-toggle !w-auto !px-3 gap-1.5 text-xs font-bold">
        <span class="text-base leading-none"><?php echo e(app()->getLocale() === 'ar' ? '🇸🇾' : '🇬🇧'); ?></span>
        <span><?php echo e(app()->getLocale() === 'ar' ? 'AR' : 'EN'); ?></span>
        <svg class="!relative !opacity-100 !w-3 !h-3" :class="open ? 'rotate-180' : ''" style="transition:transform .2s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-cloak @click.outside="open=false"
         x-transition:enter="animate-scale-in"
         class="dropdown-panel absolute <?php echo e(app()->getLocale() === 'ar' ? 'left-0' : 'right-0'); ?> mt-2 w-44 z-50 overflow-hidden origin-top">

        <a href="<?php echo e(route('set-locale', 'ar')); ?>"
           class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-hover transition
                  <?php echo e(app()->getLocale() === 'ar' ? 'text-brand-600 font-bold bg-brand-50' : 'text-main'); ?>">
            <span class="text-lg">🇸🇾</span>
            <span class="flex-1">العربية</span>
            <?php if(app()->getLocale() === 'ar'): ?>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('set-locale', 'en')); ?>"
           class="flex items-center gap-3 px-4 py-3 text-sm hover:bg-hover transition border-t border-bd
                  <?php echo e(app()->getLocale() === 'en' ? 'text-brand-600 font-bold bg-brand-50' : 'text-main'); ?>">
            <span class="text-lg">🇬🇧</span>
            <span class="flex-1">English</span>
            <?php if(app()->getLocale() === 'en'): ?>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <?php endif; ?>
        </a>
    </div>
</div><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/components/language-switcher.blade.php ENDPATH**/ ?>