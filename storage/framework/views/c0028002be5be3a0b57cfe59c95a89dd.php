
<?php $__env->startSection('title', __('app.reports')); ?>
<?php $__env->startSection('page-title', __('app.reports')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.reports_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="card !p-4 animate-fade-up">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="label"><?php echo e(__('app.classrooms')); ?></label>
                <select name="classroom_id" class="input">
                    <option value=""><?php echo e(__('app.select_option')); ?></option>
                    <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php echo e(request('classroom_id') == $c->id ? 'selected' : ''); ?>>
                        <?php echo e($c->name); ?> — <?php echo e($c->academicLevel->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end sm:col-span-2">
                <button type="submit" class="btn-primary justify-center">
                    📊 <?php echo e(__('teacher.generate_report')); ?>

                </button>
            </div>
        </form>
    </div>

    
    <?php if(count($report) > 0 && $selectedClassroom): ?>

    
    <?php
        $avgAttendance = round(collect($report)->avg('attendance_rate'), 1);
        $avgScore      = round(collect($report)->avg('avg_score'), 1);
        $atRiskCount   = collect($report)->filter(fn($r) => $r['attendance_rate'] < 75 || $r['avg_score'] < 50)->count();
        $excellentCount= collect($report)->filter(fn($r) => $r['avg_score'] >= 90)->count();
    ?>

    <div class="card animate-fade-up" style="animation-delay:.04s">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-main flex items-center gap-2">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">🏛️</span>
                <?php echo e($selectedClassroom->name); ?> — <?php echo e($selectedClassroom->academicLevel->name); ?>

            </h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 stagger">
            <?php $__currentLoopData = [
                ['label'=>__('teacher.total_students'),   'value'=>count($report),  'icon'=>'👥','ring'=>'brand'],
                ['label'=>__('teacher.avg_attendance'),   'value'=>$avgAttendance.'%','icon'=>'📅','ring'=>'info'],
                ['label'=>__('teacher.avg_score_label'),  'value'=>$avgScore.'%',    'icon'=>'📊','ring'=>'success'],
                ['label'=>__('teacher.needs_attention'),  'value'=>$atRiskCount,     'icon'=>'⚠️','ring'=>'warning','pulse'=>$atRiskCount>0],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="text-center p-4 rounded-2xl bg-<?php echo e($s['ring']); ?>-50
                        <?php echo e(($s['pulse'] ?? false) ? 'animate-pulse-glow' : ''); ?>">
                <p class="text-2xl mb-1"><?php echo e($s['icon']); ?></p>
                <p class="text-2xl font-black text-<?php echo e($s['ring']); ?>-600"><?php echo e($s['value']); ?></p>
                <p class="text-<?php echo e($s['ring']); ?>-600 text-xs font-medium mt-0.5"><?php echo e($s['label']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="card overflow-hidden !p-0 animate-fade-up" style="animation-delay:.06s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface2 border-b border-bd">
                    <tr>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">#</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('counselor.student')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('teacher.attendance_rate')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('teacher.avg_score_label')); ?></th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold"><?php echo e(__('teacher.lessons_done_label')); ?></th>
                        <th class="text-center py-3.5 px-4 text-muted font-semibold"><?php echo e(__('teacher.assignments_label')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('teacher.status_label')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('app.actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bd">
                    <?php $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $attColor   = $row['attendance_rate'] >= 90 ? 'success' : ($row['attendance_rate'] >= 75 ? 'warning' : 'danger');
                        $scoreColor = $row['avg_score'] >= 80 ? 'success' : ($row['avg_score'] >= 60 ? 'warning' : 'danger');
                        $isAtRisk   = $row['attendance_rate'] < 75 || $row['avg_score'] < 50;
                    ?>
                    <tr class="hover:bg-hover transition <?php echo e($isAtRisk ? 'bg-danger-50/40' : ''); ?> animate-slide" style="animation-delay:<?php echo e(.02 * $i); ?>s">
                        <td class="py-3 px-4 text-faint text-xs"><?php echo e($i + 1); ?></td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2.5">
                                <img src="<?php echo e($row['student']->avatar_url); ?>"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                                <div class="min-w-0">
                                    <p class="font-bold text-main truncate"><?php echo e($row['student']->name); ?></p>
                                    <p class="text-xs text-muted"><?php echo e($row['student']->studentProfile?->status_label ?? '—'); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 progress-track">
                                    <div class="progress-fill !bg-none"
                                         style="width: <?php echo e($row['attendance_rate']); ?>%;
                                                background: var(--<?php echo e($attColor); ?>-500)">
                                    </div>
                                </div>
                                <span class="text-xs font-black text-<?php echo e($attColor); ?>-600">
                                    <?php echo e($row['attendance_rate']); ?>%
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-16 progress-track">
                                    <div class="progress-fill !bg-none"
                                         style="width: <?php echo e($row['avg_score']); ?>%;
                                                background: var(--<?php echo e($scoreColor); ?>-500)">
                                    </div>
                                </div>
                                <span class="text-xs font-black text-<?php echo e($scoreColor); ?>-600">
                                    <?php echo e($row['avg_score']); ?>%
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-main"><?php echo e($row['lessons_done']); ?></td>
                        <td class="py-3 px-4 text-center font-bold text-main"><?php echo e($row['assignments_done']); ?></td>
                        <td class="py-3 px-4">
                            <?php if($isAtRisk): ?>
                            <span class="badge-red">⚠️ <?php echo e(__('teacher.needs_attention_label')); ?></span>
                            <?php elseif($row['avg_score'] >= 90): ?>
                            <span class="badge-green">⭐ <?php echo e(__('status.excellent')); ?></span>
                            <?php else: ?>
                            <span class="badge-<?php echo e($row['student']->studentProfile?->status_color ?? 'gray'); ?>">
                                <?php echo e($row['student']->studentProfile?->status_label ?? '—'); ?>

                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <a href="<?php echo e(route('teacher.reports.student', $row['student'])); ?>"
                               class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                                <?php echo e(__('app.view_details')); ?>

                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php elseif(request('classroom_id')): ?>
    <div class="card text-center py-12 animate-fade">
        <span class="text-5xl animate-float inline-block">📊</span>
        <p class="font-bold text-main mt-3"><?php echo e(__('teacher.no_report_data')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.no_report_hint')); ?></p>
    </div>

    <?php else: ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📊</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.select_classroom_prompt')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.report_select_hint')); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/reports/index.blade.php ENDPATH**/ ?>