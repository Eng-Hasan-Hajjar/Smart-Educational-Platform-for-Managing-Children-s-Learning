
<?php $__env->startSection('title', __('app.attendance')); ?>
<?php $__env->startSection('page-title', __('app.attendance')); ?>
<?php $__env->startSection('page-subtitle', __('teacher.attendance_subtitle')); ?>

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
            <div>
                <label class="label"><?php echo e(__('teacher.attendance_date')); ?></label>
                <input type="date" name="date" value="<?php echo e(request('date', today()->toDateString())); ?>" class="input">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <?php echo e(__('teacher.load_students')); ?>

                </button>
            </div>
        </form>
    </div>

    
    <?php if(request('classroom_id')): ?>
    <div class="flex items-center justify-center gap-2 animate-fade-up" style="animation-delay:.04s">
        <?php
            $currentDate = request('date', today()->toDateString());
            $prevDate = \Carbon\Carbon::parse($currentDate)->subDay()->toDateString();
            $nextDate = \Carbon\Carbon::parse($currentDate)->addDay()->toDateString();
        ?>
        <a href="?classroom_id=<?php echo e(request('classroom_id')); ?>&date=<?php echo e($prevDate); ?>"
           class="btn-outline !py-2 !px-3">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="card !p-3 text-center min-w-[160px]">
            <p class="font-black text-main text-sm">
                <?php echo e(\Carbon\Carbon::parse($currentDate)->format('d/m/Y')); ?>

            </p>
            <p class="text-xs text-muted">
                <?php echo e(__('app.' . strtolower(\Carbon\Carbon::parse($currentDate)->format('l')))); ?>

            </p>
        </div>

        <a href="?classroom_id=<?php echo e(request('classroom_id')); ?>&date=<?php echo e($nextDate); ?>"
           class="btn-outline !py-2 !px-3">
            <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    <?php endif; ?>

    
    <?php if(isset($students) && $students->count() && $selectedClassroom): ?>

    
    <?php
        $presentCount = collect($existingAttendance)->filter(fn($s) => $s === 'present')->count();
        $absentCount  = collect($existingAttendance)->filter(fn($s) => $s === 'absent')->count();
        $lateCount    = collect($existingAttendance)->filter(fn($s) => $s === 'late')->count();
        $excusedCount = collect($existingAttendance)->filter(fn($s) => $s === 'excused')->count();
        $savedCount   = count($existingAttendance);
    ?>
    <?php if($savedCount > 0): ?>
    <div class="card !p-4 animate-fade-up" style="animation-delay:.06s">
        <p class="text-xs font-bold text-muted uppercase tracking-widest mb-3"><?php echo e(__('teacher.attendance_summary')); ?></p>
        <div class="grid grid-cols-4 gap-3">
            <?php $__currentLoopData = [
                ['label'=>__('status.present'), 'value'=>$presentCount, 'ring'=>'success', 'icon'=>'✅'],
                ['label'=>__('status.absent'),  'value'=>$absentCount,  'ring'=>'danger',  'icon'=>'❌'],
                ['label'=>__('status.late'),    'value'=>$lateCount,    'ring'=>'warning', 'icon'=>'⏰'],
                ['label'=>__('status.excused'), 'value'=>$excusedCount, 'ring'=>'info',    'icon'=>'📋'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="text-center p-3 rounded-2xl bg-<?php echo e($s['ring']); ?>-50">
                <p class="text-xl mb-1"><?php echo e($s['icon']); ?></p>
                <p class="text-2xl font-black text-<?php echo e($s['ring']); ?>-600"><?php echo e($s['value']); ?></p>
                <p class="text-<?php echo e($s['ring']); ?>-600 text-xs font-medium"><?php echo e($s['label']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('teacher.attendance.store')); ?>"
          x-data="{
              statuses: <?php echo \Illuminate\Support\Js::from(
                  $students->pluck('id')->mapWithKeys(fn($id) =>
                      [$id => $existingAttendance[$id] ?? 'present']
                  )->toArray()
              )->toHtml() ?>,
              markAll(status) {
                  Object.keys(this.statuses).forEach(id => this.statuses[id] = status);
              },
              getCount(status) {
                  return Object.values(this.statuses).filter(s => s === status).length;
              }
          }"
          class="space-y-4 animate-fade-up" style="animation-delay:.08s">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="classroom_id" value="<?php echo e($selectedClassroom->id); ?>">
        <input type="hidden" name="date" value="<?php echo e($date); ?>">

        
        <div class="card !p-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <p class="font-bold text-main"><?php echo e($selectedClassroom->name); ?></p>
                    <p class="text-xs text-muted mt-0.5"><?php echo e($students->count()); ?> <?php echo e(__('teacher.students_total')); ?></p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs text-muted font-medium"><?php echo e(__('teacher.mark_all')); ?>:</span>
                    <?php $__currentLoopData = [
                        ['status'=>'present', 'label'=>__('status.present'), 'icon'=>'✅', 'style'=>'bg-success-50 text-success-600 hover:bg-success-500 hover:text-white'],
                        ['status'=>'absent',  'label'=>__('status.absent'),  'icon'=>'❌', 'style'=>'bg-danger-50 text-danger-600 hover:bg-danger-500 hover:text-white'],
                        ['status'=>'late',    'label'=>__('status.late'),    'icon'=>'⏰', 'style'=>'bg-warning-50 text-warning-600 hover:bg-warning-500 hover:text-white'],
                        ['status'=>'excused', 'label'=>__('status.excused'), 'icon'=>'📋', 'style'=>'bg-info-50 text-info-600 hover:bg-info-500 hover:text-white'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" @click="markAll('<?php echo e($btn['status']); ?>')"
                            class="text-xs font-bold px-3 py-1.5 rounded-xl transition <?php echo e($btn['style']); ?>">
                        <?php echo e($btn['icon']); ?> <?php echo e($btn['label']); ?>

                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="mt-3 pt-3 border-t border-bd grid grid-cols-4 gap-2 text-center text-xs">
                <?php $__currentLoopData = ['present'=>['success','✅'], 'absent'=>['danger','❌'], 'late'=>['warning','⏰'], 'excused'=>['info','📋']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st=>[$color,$icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-<?php echo e($color); ?>-50 rounded-xl py-2">
                    <span><?php echo e($icon); ?></span>
                    <span class="font-black text-<?php echo e($color); ?>-600 ms-1" x-text="getCount('<?php echo e($st); ?>')"></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="space-y-2">
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card !p-3 animate-slide" style="animation-delay:<?php echo e(.02 * $i); ?>s">
                <div class="flex items-center gap-3">

                    
                    <span class="w-7 h-7 rounded-lg bg-surface2 text-muted text-xs font-black flex items-center justify-center flex-shrink-0">
                        <?php echo e($i + 1); ?>

                    </span>

                    
                    <img src="<?php echo e($student->avatar_url); ?>" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm text-main truncate"><?php echo e($student->name); ?></p>
                        <p class="text-xs text-muted"><?php echo e($student->studentProfile?->student_number ?? ''); ?></p>
                    </div>

                    
                    <input type="hidden" :name="'student_ids[]'" value="<?php echo e($student->id); ?>">
                    <input type="hidden" :name="'status[<?php echo e($student->id); ?>]'" :value="statuses[<?php echo e($student->id); ?>]">

                    
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <?php $__currentLoopData = [
                            ['status'=>'present', 'icon'=>'✅', 'label'=>__('status.present'), 'active'=>'bg-success-500 text-white border-success-500', 'idle'=>'border-bd text-muted hover:border-success-400 hover:bg-success-50'],
                            ['status'=>'absent',  'icon'=>'❌', 'label'=>__('status.absent'),  'active'=>'bg-danger-500 text-white border-danger-500',  'idle'=>'border-bd text-muted hover:border-danger-400 hover:bg-danger-50'],
                            ['status'=>'late',    'icon'=>'⏰', 'label'=>__('status.late'),    'active'=>'bg-warning-500 text-white border-warning-500', 'idle'=>'border-bd text-muted hover:border-warning-400 hover:bg-warning-50'],
                            ['status'=>'excused', 'icon'=>'📋', 'label'=>__('status.excused'), 'active'=>'bg-info-500 text-white border-info-500',       'idle'=>'border-bd text-muted hover:border-info-400 hover:bg-info-50'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button"
                                @click="statuses[<?php echo e($student->id); ?>] = '<?php echo e($btn['status']); ?>'"
                                :class="statuses[<?php echo e($student->id); ?>] === '<?php echo e($btn['status']); ?>'
                                    ? '<?php echo e($btn['active']); ?>'
                                    : '<?php echo e($btn['idle']); ?>'"
                                class="w-9 h-9 sm:w-auto sm:px-3 sm:h-9 rounded-xl border text-xs font-bold transition-all flex items-center justify-center gap-1"
                                :title="'<?php echo e($btn['label']); ?>'">
                            <span><?php echo e($btn['icon']); ?></span>
                            <span class="hidden sm:inline"><?php echo e($btn['label']); ?></span>
                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="flex justify-end animate-fade-up" style="animation-delay:.3s">
            <button type="submit" class="btn-primary !py-3 !px-8 text-base">
                💾 <?php echo e(__('teacher.save_attendance')); ?>

            </button>
        </div>
    </form>

    <?php elseif(request('classroom_id') && (!isset($students) || $students->count() === 0)): ?>
    <div class="card text-center py-12 animate-fade">
        <span class="text-5xl animate-float inline-block">👥</span>
        <p class="font-bold text-main mt-3"><?php echo e(__('teacher.no_students_in_classroom')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.no_students_hint')); ?></p>
    </div>

    <?php else: ?>
    
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">📅</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('teacher.select_classroom_prompt')); ?></p>
        <p class="text-muted text-sm mt-1"><?php echo e(__('teacher.select_classroom_hint')); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/teacher/attendance/index.blade.php ENDPATH**/ ?>