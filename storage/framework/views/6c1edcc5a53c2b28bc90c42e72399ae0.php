
<?php $__env->startSection('title', __('teacher.my_students')); ?>
<?php $__env->startSection('page-title', __('teacher.my_students')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.my_students_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="card !p-4 animate-fade-up">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       class="input ps-10" placeholder="<?php echo e(__('teacher.search_students')); ?>">
            </div>
            <select name="classroom_id" class="input">
                <option value=""><?php echo e(__('app.classrooms')); ?></option>
                <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php echo e(request('classroom_id') == $c->id ? 'selected' : ''); ?>>
                    <?php echo e($c->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="status" class="input">
                <option value=""><?php echo e(__('teacher.all_statuses')); ?></option>
                <?php $__currentLoopData = ['excellent'=>__('status.excellent'),'good'=>__('status.good'),'average'=>__('status.average'),'needs_support'=>__('status.needs_support'),'at_risk'=>__('status.at_risk')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($v); ?>" <?php echo e(request('status') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary flex-1 justify-center">
                    🔍 <?php echo e(__('app.search')); ?>

                </button>
                <?php if(request()->hasAny(['search','classroom_id','status'])): ?>
                <a href="<?php echo e(route('teacher.students.index')); ?>" class="btn-outline px-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <?php if($students->count()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 stagger">
        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('teacher.students.show', $student)); ?>"
           class="card card-hover group block">

            <div class="flex items-center gap-3 mb-4">
                <div class="avatar-ring flex-shrink-0">
                    <img src="<?php echo e($student->avatar_url); ?>" class="w-12 h-12 object-cover" alt="">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-main group-hover:text-brand-500 transition truncate">
                        <?php echo e($student->name); ?>

                    </p>
                    <p class="text-xs text-muted">
                        <?php echo e($student->studentProfile?->academicLevel?->name ?? '—'); ?>

                    </p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="badge-<?php echo e($student->studentProfile?->status_color ?? 'gray'); ?>">
                            <?php echo e($student->studentProfile?->status_label ?? '—'); ?>

                        </span>
                        <?php $__currentLoopData = $student->classrooms->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge-brand"><?php echo e($c->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <?php if($student->gamification): ?>
            <div class="bg-surface2 rounded-2xl p-3 mb-3">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-muted font-semibold">
                        🏆 <?php echo e($student->gamification->level_title); ?>

                        (<?php echo e(__('student.level')); ?> <?php echo e($student->gamification->level); ?>)
                    </span>
                    <span class="font-black text-brand-500">
                        <?php echo e(number_format($student->gamification->total_points)); ?> <?php echo e(__('student.pts')); ?>

                    </span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" style="width: <?php echo e($student->gamification->level_progress); ?>%"></div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="flex items-center justify-end gap-1 text-xs text-brand-500 font-bold group-hover:gap-2 transition-all">
                <?php echo e(__('teacher.view_student_profile')); ?>

                <svg class="w-3.5 h-3.5 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="flex justify-center"><?php echo e($students->withQueryString()->links()); ?></div>

    <?php else: ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">👨‍🎓</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.no_students_found')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.no_students_found_hint')); ?></p>
        <?php if(request()->hasAny(['search','classroom_id','status'])): ?>
        <a href="<?php echo e(route('teacher.students.index')); ?>" class="btn-outline mt-4 inline-flex">
            <?php echo e(__('teacher.clear_filters')); ?>

        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/students/index.blade.php ENDPATH**/ ?>