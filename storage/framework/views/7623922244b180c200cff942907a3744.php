
<?php $__env->startSection('title', __('admin.welcome')); ?>
<?php $__env->startSection('page-title', __('app.dashboard')); ?>

<?php
    $hour = now()->hour;
    $greetingKey = match(true) {
        $hour < 12 => 'greeting_morning',
        $hour < 17 => 'greeting_afternoon',
        $hour < 21 => 'greeting_evening',
        default    => 'greeting_night',
    };
    $dayName = strtolower(now()->format('l'));

    $monthLabels = __('admin.months');
    $monthlyData = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlyData[] = $monthlyUsers[$m] ?? 0;
    }

    $planKeys   = ['basic', 'premium', 'enterprise'];
    $planColors = ['basic' => 'info', 'premium' => 'brand', 'enterprise' => 'accent'];
    $totalPlans = array_sum($subscriptionStats) ?: 1;
?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 text-white animate-fade-up"
         style="background: linear-gradient(135deg, var(--brand-600), var(--brand-700) 65%, var(--bg-sidebar-to))">

        <div class="absolute w-64 h-64 rounded-full bg-accent-400/20 blur-3xl -top-16 end-[-3rem] animate-pulse-glow"></div>
        <div class="absolute w-72 h-72 rounded-full bg-brand-400/15 blur-3xl -bottom-20 start-[-4rem] animate-pulse-glow" style="animation-delay:1s"></div>
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 26px 26px;"></div>

        <div class="absolute top-8 end-1/4 text-3xl animate-float opacity-30 hidden sm:block">🌐</div>
        <div class="absolute bottom-10 end-12 text-2xl animate-float opacity-30 hidden sm:block" style="animation-delay:.8s">⚙️</div>

        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">

            <div class="avatar-ring flex-shrink-0 animate-scale-in !p-[3px]">
                <img src="<?php echo e(auth()->user()->avatar_url); ?>" class="w-16 h-16 sm:w-20 sm:h-20 object-cover" alt="">
            </div>

            <div class="flex-1 text-center sm:text-start">
                <p class="text-white/65 text-sm"><?php echo e(__('app.'.$greetingKey)); ?> 👋</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold mt-0.5"><?php echo e(auth()->user()->name); ?></h2>
                <p class="text-white/60 text-sm mt-1">
                    <?php echo e(__('admin.subtitle')); ?> · <?php echo e(__('app.'.$dayName)); ?>, <?php echo e(now()->format('d/m/Y')); ?>

                </p>
            </div>

            <?php if($newUsersThisWeek > 0): ?>
            <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-2xl px-4 py-2.5 flex-shrink-0 animate-pulse-glow">
                <span class="text-xl">🆕</span>
                <div class="text-start">
                    <p class="font-black text-lg leading-none">+<?php echo e($newUsersThisWeek); ?></p>
                    <p class="text-white/60 text-[10px]"><?php echo e(__('admin.new_users_week')); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="hidden lg:flex flex-col items-center justify-center flex-shrink-0">
                <span class="text-5xl animate-float">🛡️</span>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 stagger">
        <?php $__currentLoopData = [
            ['label' => __('admin.total_schools'),     'value' => $stats['schools'],        'icon' => '🏫', 'ring' => 'brand'],
            ['label' => __('admin.active_schools'),    'value' => $stats['active_schools'], 'icon' => '✅', 'ring' => 'success'],
            ['label' => __('admin.total_students'),    'value' => $stats['students'],       'icon' => '👨‍🎓', 'ring' => 'info'],
            ['label' => __('admin.total_teachers'),    'value' => $stats['teachers'],       'icon' => '👨‍🏫', 'ring' => 'warning'],
            ['label' => __('admin.total_parents'),     'value' => $stats['parents'],        'icon' => '👨‍👩‍👧', 'ring' => 'brand'],
            ['label' => __('admin.published_content'), 'value' => $stats['lessons'] + $stats['quizzes'], 'icon' => '📚', 'ring' => 'success'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card card-hover">
            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl mb-3
                        bg-<?php echo e($s['ring']); ?>-50 text-<?php echo e($s['ring']); ?>-600">
                <?php echo e($s['icon']); ?>

            </div>
            <p class="text-2xl sm:text-3xl font-black text-main"
               x-data="{ display: 0 }"
               x-init="
                    let target = <?php echo e($s['value']); ?>, start=null, dur=900;
                    function step(ts){ if(!start) start=ts;
                        let p=Math.min((ts-start)/dur,1);
                        display = Math.floor(p*target);
                        if(p<1) requestAnimationFrame(step); else display=target;
                    }
                    requestAnimationFrame(step);
               "
               x-text="display.toLocaleString()"></p>
            <p class="text-muted text-xs mt-1"><?php echo e($s['label']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="card lg:col-span-2 animate-fade-up" style="animation-delay:.05s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-base">🏫</span>
                    <?php echo e(__('admin.recent_schools')); ?>

                </h3>
                <a href="<?php echo e(route('admin.schools.index')); ?>" class="text-brand-500 hover:text-brand-700 text-xs font-bold transition">
                    <?php echo e(__('app.view_all')); ?>

                </a>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $recentSchools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 p-3 rounded-2xl border border-bd hover:bg-hover transition mb-2 animate-slide">
                <img src="<?php echo e($school->logo_url); ?>" class="w-11 h-11 rounded-xl object-cover flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm text-main truncate"><?php echo e($school->name); ?></p>
                    <p class="text-xs text-muted">
                        <?php echo e($school->city ?? '—'); ?>

                        · 👨‍🎓 <?php echo e($school->students()->count()); ?> <?php echo e(__('admin.students_short')); ?>

                        · 👨‍🏫 <?php echo e($school->teachers()->count()); ?> <?php echo e(__('admin.teachers_short')); ?>

                    </p>
                </div>
                <span class="badge-<?php echo e($school->status === 'active' ? 'green' : 'red'); ?> flex-shrink-0">
                    <?php echo e(__('status.'.$school->status)); ?>

                </span>
                <a href="<?php echo e(route('admin.schools.edit', $school)); ?>"
                   class="text-xs font-bold text-brand-500 hover:text-brand-700 transition flex-shrink-0 hidden sm:inline">
                    <?php echo e(__('admin.edit')); ?>

                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-10 animate-fade">
                <span class="text-5xl animate-float inline-block">🏫</span>
                <p class="text-muted text-sm mt-3 font-bold"><?php echo e(__('admin.no_schools_yet')); ?></p>
                <a href="<?php echo e(route('admin.schools.create')); ?>" class="btn-primary mt-3 !py-2 !px-4 text-xs">
                    <?php echo e(__('admin.add_school')); ?>

                </a>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="card animate-fade-up" style="animation-delay:.1s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-accent-50 text-accent-600 flex items-center justify-center text-base">💎</span>
                    <?php echo e(__('admin.subscription_distribution')); ?>

                </h3>
            </div>

            <div class="space-y-4">
                <?php $__currentLoopData = $planKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $count = $subscriptionStats[$plan] ?? 0;
                    $pct   = round(($count / $totalPlans) * 100);
                    $color = $planColors[$plan];
                ?>
                <div>
                    <div class="flex items-center justify-between mb-1.5 text-sm">
                        <span class="font-bold text-main"><?php echo e(__('admin.'.$plan)); ?></span>
                        <span class="text-<?php echo e($color); ?>-600 font-black"><?php echo e($count); ?></span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill !bg-none animate-fade-up"
                             style="width: <?php echo e($pct); ?>%; background: var(--<?php echo e($color); ?>-500); animation-delay: .15s"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="card lg:col-span-2 animate-fade-up" style="animation-delay:.15s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-success-50 text-success-600 flex items-center justify-center text-base">📈</span>
                    <?php echo e(__('admin.monthly_growth')); ?>

                </h3>
            </div>
            <div class="relative h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        
        <div class="card animate-fade-up" style="animation-delay:.2s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-main flex items-center gap-2">
                    <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-base">⚡</span>
                    <?php echo e(__('admin.quick_actions')); ?>

                </h3>
            </div>

            <div class="space-y-3 stagger">
                <a href="<?php echo e(route('admin.schools.create')); ?>"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-brand-50 hover:bg-brand-100 hover:-translate-y-1 transition-all text-brand-600">
                    <span class="text-2xl">🏫</span>
                    <span class="text-sm font-bold flex-1"><?php echo e(__('admin.add_school')); ?></span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('admin.users.create')); ?>"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-info-50 hover:bg-info-50/70 hover:-translate-y-1 transition-all text-info-600">
                    <span class="text-2xl">👤</span>
                    <span class="text-sm font-bold flex-1"><?php echo e(__('admin.add_user')); ?></span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('admin.schools.index')); ?>"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-success-50 hover:bg-success-50/70 hover:-translate-y-1 transition-all text-success-600">
                    <span class="text-2xl">🏛️</span>
                    <span class="text-sm font-bold flex-1"><?php echo e(__('admin.manage_schools')); ?></span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('admin.users.index')); ?>"
                   class="flex items-center gap-3 p-4 rounded-2xl bg-accent-50 hover:bg-accent-50/70 hover:-translate-y-1 transition-all text-accent-600">
                    <span class="text-2xl">👥</span>
                    <span class="text-sm font-bold flex-1"><?php echo e(__('admin.manage_users')); ?></span>
                    <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const root        = getComputedStyle(document.documentElement);
        const brandColor  = root.getPropertyValue('--brand-500').trim();
        const mutedColor  = root.getPropertyValue('--text-muted').trim();
        const borderColor = root.getPropertyValue('--border-app').trim();

        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthLabels, 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($monthlyData, 15, 512) ?>,
                    backgroundColor: brandColor,
                    borderRadius: 8,
                    maxBarThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: mutedColor, font: { size: 11 } } },
                    y: { beginAtZero: true, grid: { color: borderColor }, ticks: { color: mutedColor, precision: 0, font: { size: 11 } } }
                }
            }
        });
    })();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>