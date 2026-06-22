
<?php $__env->startSection('title', __('app.quizzes')); ?>
<?php $__env->startSection('page-title', __('app.quizzes')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.quizzes_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($quizzes->total()); ?> <?php echo e(__('teacher.quizzes_count')); ?></p>
        <a href="<?php echo e(route('teacher.quizzes.create')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('teacher.new_quiz')); ?>

        </a>
    </div>

    
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="status" class="input sm:w-48">
                <option value=""><?php echo e(__('app.all')); ?></option>
                <option value="draft"     <?php echo e(request('status') === 'draft'     ? 'selected' : ''); ?>><?php echo e(__('status.draft')); ?></option>
                <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>><?php echo e(__('status.published')); ?></option>
                <option value="archived"  <?php echo e(request('status') === 'archived'  ? 'selected' : ''); ?>><?php echo e(__('status.archived')); ?></option>
            </select>
            <select name="type" class="input sm:w-48">
                <option value=""><?php echo e(__('teacher.all_types')); ?></option>
                <?php $__currentLoopData = ['lesson_quiz'=>__('teacher.type_lesson_quiz'), 'unit_test'=>__('teacher.type_unit_test'), 'midterm'=>__('teacher.type_midterm'), 'final'=>__('teacher.type_final'), 'practice'=>__('teacher.type_practice')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($v); ?>" <?php echo e(request('type') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn-outline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                <?php echo e(__('app.filter')); ?>

            </button>
            <?php if(request()->hasAny(['status','type'])): ?>
            <a href="<?php echo e(route('teacher.quizzes.index')); ?>" class="btn-outline text-danger-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            <?php endif; ?>
        </form>
    </div>

    
    <?php $__empty_1 = true; $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="card card-hover animate-fade-up" style="animation-delay:<?php echo e(.06 + $loop->index * .04); ?>s">
        <div class="flex items-start gap-4">

            
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0
                        <?php echo e($quiz->status === 'published' ? 'bg-success-50 text-success-600' : 'bg-hover text-faint'); ?>">
                📝
            </div>

            
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <span class="badge-<?php echo e($quiz->status === 'published' ? 'green' : ($quiz->status === 'archived' ? 'gray' : 'yellow')); ?>">
                        <?php echo e(__('status.'.$quiz->status)); ?>

                    </span>
                    <span class="badge-brand"><?php echo e(__('teacher.type_'.$quiz->type)); ?></span>
                    <?php if($quiz->subject): ?>
                    <span class="text-xs text-muted"><?php echo e($quiz->subject->name); ?></span>
                    <?php endif; ?>
                </div>

                <a href="<?php echo e(route('teacher.quizzes.edit', $quiz)); ?>"
                   class="font-bold text-main hover:text-brand-500 transition text-base">
                    <?php echo e($quiz->title); ?>

                </a>

                
                <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-faint">
                    <span class="flex items-center gap-1">
                        ❓ <?php echo e($quiz->questions_count); ?> <?php echo e(__('teacher.questions')); ?>

                    </span>
                    <span class="flex items-center gap-1">
                        🎯 <?php echo e($quiz->total_marks); ?> <?php echo e(__('teacher.marks')); ?>

                    </span>
                    <span class="flex items-center gap-1">
                        ⏱ <?php echo e($quiz->duration_minutes ?? '—'); ?> <?php echo e(__('student.min')); ?>

                    </span>
                    <span class="flex items-center gap-1">
                        🔄 <?php echo e($quiz->max_attempts); ?> <?php echo e(__('teacher.attempts')); ?>

                    </span>
                    <span class="flex items-center gap-1">
                        👥 <?php echo e($quiz->attempts_count); ?> <?php echo e(__('teacher.students_attempted')); ?>

                    </span>
                </div>

                
                <?php if($quiz->available_from || $quiz->available_until): ?>
                <div class="flex items-center gap-2 mt-2 text-xs text-muted">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <?php echo e($quiz->available_from?->format('d/m/Y H:i') ?? '—'); ?>

                    →
                    <?php echo e($quiz->available_until?->format('d/m/Y H:i') ?? '—'); ?>

                </div>
                <?php endif; ?>

                
                <?php if($quiz->attempts_count > 0): ?>
                <div class="mt-3 flex items-center gap-2">
                    <div class="flex-1 progress-track">
                        <?php $passed = $quiz->attempts()->where('is_passed', true)->count(); ?>
                        <div class="progress-fill !bg-none"
                             style="width: <?php echo e($quiz->attempts_count > 0 ? round($passed/$quiz->attempts_count*100) : 0); ?>%;
                                    background: var(--success-500)"></div>
                    </div>
                    <span class="text-xs text-success-600 font-bold flex-shrink-0">
                        <?php echo e($passed); ?>/<?php echo e($quiz->attempts_count); ?> <?php echo e(__('teacher.passed')); ?>

                    </span>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="<?php echo e(route('teacher.quizzes.edit', $quiz)); ?>" class="btn-outline !py-2 !px-3 text-xs">
                    ✏️ <?php echo e(__('app.edit')); ?>

                </a>
                <form method="POST" action="<?php echo e(route('teacher.quizzes.publish', $quiz)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit"
                            class="w-full text-xs font-bold px-3 py-2 rounded-xl transition
                                   <?php echo e($quiz->status === 'published'
                                        ? 'bg-warning-50 text-warning-600 hover:bg-warning-50/70'
                                        : 'bg-success-50 text-success-600 hover:bg-success-50/70'); ?>">
                        <?php echo e($quiz->status === 'published' ? '🙈 '.__('teacher.unpublish') : '🚀 '.__('teacher.publish')); ?>

                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('teacher.quizzes.destroy', $quiz)); ?>"
                      onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full text-xs font-bold px-3 py-2 rounded-xl bg-danger-50 text-danger-600 hover:bg-danger-50/70 transition">
                        🗑️ <?php echo e(__('app.delete')); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📝</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.no_quizzes_yet')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.no_quizzes_hint')); ?></p>
        <a href="<?php echo e(route('teacher.quizzes.create')); ?>" class="btn-primary mt-5 inline-flex">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('teacher.create_first_quiz')); ?>

        </a>
    </div>
    <?php endif; ?>

    <?php if($quizzes->hasPages()): ?>
    <div class="flex justify-center animate-fade-up">
        <?php echo e($quizzes->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/quizzes/index.blade.php ENDPATH**/ ?>