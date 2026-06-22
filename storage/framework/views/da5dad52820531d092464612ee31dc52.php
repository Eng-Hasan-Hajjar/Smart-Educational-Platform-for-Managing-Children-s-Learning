
<?php $__env->startSection('title', __('teacher.new_quiz')); ?>
<?php $__env->startSection('page-title', __('teacher.new_quiz')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.new_quiz_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto animate-fade-up">
    <form method="POST" action="<?php echo e(route('teacher.quizzes.store')); ?>" class="space-y-6"
          x-data="{ loading: false }" @submit="loading = true">
        <?php echo csrf_field(); ?>

        
        <div class="card space-y-5">
            <div class="flex items-center gap-3 pb-3 border-b border-bd">
                <span class="w-9 h-9 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">⚙️</span>
                <h3 class="font-bold text-main"><?php echo e(__('teacher.quiz_settings')); ?></h3>
            </div>

            <?php if(request('lesson_id')): ?>
            <input type="hidden" name="lesson_id" value="<?php echo e(request('lesson_id')); ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                
                <div class="md:col-span-2">
                    <label class="label"><?php echo e(__('teacher.quiz_title')); ?> *</label>
                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required
                           class="input" placeholder="<?php echo e(__('teacher.quiz_title_placeholder')); ?>">
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.quiz_type')); ?></label>
                    <select name="type" class="input">
                        <?php $__currentLoopData = ['lesson_quiz'=>__('teacher.type_lesson_quiz'), 'unit_test'=>__('teacher.type_unit_test'), 'midterm'=>__('teacher.type_midterm'), 'final'=>__('teacher.type_final'), 'practice'=>__('teacher.type_practice')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php echo e(old('type') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('app.subjects')); ?></label>
                    <select name="subject_id" class="input">
                        <option value=""><?php echo e(__('app.select_option')); ?></option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e(old('subject_id') == $s->id ? 'selected' : ''); ?>>
                            <?php echo e($s->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.total_marks')); ?> *</label>
                    <input type="number" name="total_marks" value="<?php echo e(old('total_marks', 100)); ?>"
                           min="1" required class="input">
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.pass_marks')); ?> *</label>
                    <input type="number" name="pass_marks" value="<?php echo e(old('pass_marks', 50)); ?>"
                           min="1" required class="input">
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.duration_minutes')); ?></label>
                    <div class="relative">
                        <input type="number" name="duration_minutes" value="<?php echo e(old('duration_minutes', 30)); ?>"
                               min="1" class="input ps-10">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none text-sm">⏱</span>
                    </div>
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.max_attempts')); ?></label>
                    <input type="number" name="max_attempts" value="<?php echo e(old('max_attempts', 1)); ?>"
                           min="1" max="10" class="input">
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.available_from')); ?></label>
                    <input type="datetime-local" name="available_from" value="<?php echo e(old('available_from')); ?>" class="input">
                </div>

                
                <div>
                    <label class="label"><?php echo e(__('teacher.available_until')); ?></label>
                    <input type="datetime-local" name="available_until" value="<?php echo e(old('available_until')); ?>" class="input">
                </div>

                
                <div class="md:col-span-2">
                    <label class="label"><?php echo e(__('teacher.quiz_description')); ?></label>
                    <textarea name="description" rows="2" class="input resize-none"
                              placeholder="<?php echo e(__('teacher.quiz_description_placeholder')); ?>"><?php echo e(old('description')); ?></textarea>
                </div>

                
                <div class="md:col-span-2">
                    <label class="label"><?php echo e(__('teacher.quiz_instructions')); ?></label>
                    <textarea name="instructions" rows="2" class="input resize-none"
                              placeholder="<?php echo e(__('teacher.quiz_instructions_placeholder')); ?>"><?php echo e(old('instructions')); ?></textarea>
                </div>
            </div>

            
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 border-t border-bd">
                <?php $__currentLoopData = [
                    ['name'=>'shuffle_questions', 'label'=>__('teacher.shuffle_questions'), 'icon'=>'🔀'],
                    ['name'=>'shuffle_options',   'label'=>__('teacher.shuffle_options'),   'icon'=>'🔁'],
                    ['name'=>'show_results_immediately', 'label'=>__('teacher.show_results_immediately'), 'icon'=>'⚡', 'checked'=>true],
                    ['name'=>'show_correct_answers',     'label'=>__('teacher.show_correct_answers'),     'icon'=>'✅'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-bd hover:bg-hover cursor-pointer select-none transition">
                    <input type="checkbox" name="<?php echo e($opt['name']); ?>" value="1"
                           <?php echo e(($opt['checked'] ?? false) || old($opt['name']) ? 'checked' : ''); ?>

                           class="w-4 h-4 rounded-lg accent-brand-500">
                    <span class="text-xs font-medium text-main"><?php echo e($opt['icon']); ?> <?php echo e($opt['label']); ?></span>
                </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <a href="<?php echo e(route('teacher.quizzes.index')); ?>" class="btn-outline w-full sm:w-auto justify-center">
                <?php echo e(__('app.cancel')); ?>

            </a>
            <div class="flex gap-3 w-full sm:w-auto">
                <button type="submit" name="status" value="draft" class="btn-outline flex-1 sm:flex-none justify-center">
                    💾 <?php echo e(__('teacher.save_draft')); ?>

                </button>
                <button type="submit" name="status" value="published" :disabled="loading"
                        class="btn-primary flex-1 sm:flex-none justify-center">
                    <span x-show="!loading">🚀 <?php echo e(__('teacher.quiz_create_and_add_questions')); ?></span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <?php echo e(__('app.loading')); ?>

                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/quizzes/create.blade.php ENDPATH**/ ?>