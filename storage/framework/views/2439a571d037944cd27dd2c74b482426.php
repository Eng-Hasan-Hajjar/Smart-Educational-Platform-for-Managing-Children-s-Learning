
<?php $__env->startSection('title', __('app.assignments')); ?>
<?php $__env->startSection('page-title', __('app.assignments')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.assignments_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($assignments->total()); ?> <?php echo e(__('teacher.assignments_count')); ?></p>
        <a href="<?php echo e(route('teacher.assignments.create')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('teacher.new_assignment')); ?>

        </a>
    </div>

    
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="input sm:w-48">
                <option value=""><?php echo e(__('app.all')); ?></option>
                <option value="draft"     <?php echo e(request('status') === 'draft'     ? 'selected' : ''); ?>><?php echo e(__('status.draft')); ?></option>
                <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>><?php echo e(__('status.published')); ?></option>
                <option value="closed"    <?php echo e(request('status') === 'closed'    ? 'selected' : ''); ?>><?php echo e(__('teacher.assignment_closed')); ?></option>
            </select>
            <select name="subject_id" class="input sm:w-48">
                <option value=""><?php echo e(__('app.subjects')); ?></option>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>" <?php echo e(request('subject_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                <?php echo e(__('app.filter')); ?>

            </button>
            <?php if(request()->hasAny(['status','subject_id'])): ?>
            <a href="<?php echo e(route('teacher.assignments.index')); ?>" class="btn-outline text-danger-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            <?php endif; ?>
        </form>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $overdue        = $assignment->isOverdue() && $assignment->status === 'published';
        $totalStudents  = $assignment->classroom->students()->count();
        $submittedCount = $assignment->submissions_count;
        $gradedCount    = $assignment->graded_count;
        $submitPct      = $totalStudents > 0 ? round($submittedCount / $totalStudents * 100) : 0;
    ?>

    <div class="card card-hover animate-fade-up" style="animation-delay:<?php echo e(.06 + $loop->index * .04); ?>s">
        <div class="flex items-start gap-4">

            
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        <?php echo e($assignment->status === 'published' && !$overdue
                            ? 'bg-success-50 text-success-600'
                            : ($overdue ? 'bg-danger-50 text-danger-600' : 'bg-hover text-faint')); ?>">
                <?php echo e($overdue ? '⌛' : ($assignment->status === 'published' ? '📋' : '📝')); ?>

            </div>

            
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="badge-<?php echo e($assignment->status === 'published' ? ($overdue ? 'red' : 'green') : ($assignment->status === 'closed' ? 'gray' : 'yellow')); ?>">
                        <?php echo e($overdue ? __('teacher.overdue') : __('status.'.$assignment->status)); ?>

                    </span>
                    <?php if($assignment->subject): ?>
                    <span class="text-xs text-muted"><?php echo e($assignment->subject->name); ?></span>
                    <?php endif; ?>
                    <span class="text-faint text-xs">·</span>
                    <span class="text-xs text-muted"><?php echo e($assignment->classroom->name); ?></span>
                </div>

                <a href="<?php echo e(route('teacher.assignments.edit', $assignment)); ?>"
                   class="font-bold text-main hover:text-brand-500 transition text-base">
                    <?php echo e($assignment->title); ?>

                </a>

                <?php if($assignment->description): ?>
                <p class="text-muted text-xs mt-1 line-clamp-2"><?php echo e($assignment->description); ?></p>
                <?php endif; ?>

                
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-faint">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <?php echo e(__('teacher.due')); ?>: <span class="<?php echo e($overdue ? 'text-danger-600 font-bold' : ''); ?>"><?php echo e($assignment->due_date->format('d/m/Y - H:i')); ?></span>
                    </span>
                    <span class="flex items-center gap-1">
                        🎯 <?php echo e($assignment->total_marks); ?> <?php echo e(__('teacher.marks')); ?>

                    </span>
                    <span class="flex items-center gap-1">
                        📤 <?php echo e($submittedCount); ?>/<?php echo e($totalStudents); ?> <?php echo e(__('teacher.submitted')); ?>

                    </span>
                    <?php if($gradedCount > 0): ?>
                    <span class="flex items-center gap-1 text-success-600">
                        ✅ <?php echo e($gradedCount); ?> <?php echo e(__('teacher.graded')); ?>

                    </span>
                    <?php endif; ?>
                </div>

                
                <div class="mt-3 flex items-center gap-2">
                    <div class="flex-1 progress-track">
                        <div class="progress-fill !bg-none"
                             style="width: <?php echo e($submitPct); ?>%;
                                    background: <?php echo e($submitPct >= 80 ? 'var(--success-500)' : ($submitPct >= 40 ? 'var(--warning-500)' : 'var(--brand-500)')); ?>">
                        </div>
                    </div>
                    <span class="text-xs font-bold text-muted flex-shrink-0"><?php echo e($submitPct); ?>%</span>
                </div>
            </div>

            
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="<?php echo e(route('teacher.assignments.submissions', $assignment)); ?>"
                   class="btn-primary !py-2 !px-3 text-xs relative">
                    📥 <?php echo e(__('teacher.submissions')); ?>

                    <?php if($submittedCount - $gradedCount > 0): ?>
                    <span class="absolute -top-1 -end-1 w-4 h-4 bg-danger-500 text-white text-[10px] font-black rounded-full flex items-center justify-center">
                        <?php echo e($submittedCount - $gradedCount); ?>

                    </span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(route('teacher.assignments.edit', $assignment)); ?>" class="btn-outline !py-2 !px-3 text-xs">
                    ✏️ <?php echo e(__('app.edit')); ?>

                </a>
                <form method="POST" action="<?php echo e(route('teacher.assignments.destroy', $assignment)); ?>"
                      onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 transition">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📋</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.no_assignments_yet')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.no_assignments_hint')); ?></p>
        <a href="<?php echo e(route('teacher.assignments.create')); ?>" class="btn-primary mt-5 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('teacher.create_first_assignment')); ?>

        </a>
    </div>
    <?php endif; ?>

    <?php if($assignments->hasPages()): ?>
    <div class="flex justify-center animate-fade-up">
        <?php echo e($assignments->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/assignments/index.blade.php ENDPATH**/ ?>