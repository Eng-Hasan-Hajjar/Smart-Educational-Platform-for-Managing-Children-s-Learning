
<?php $__env->startSection('title', __('app.users')); ?>
<?php $__env->startSection('page-title', __('app.users')); ?>
<?php $__env->startSection('page-subtitle', __('admin.users_subtitle')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm"><?php echo e($users->total()); ?> <?php echo e(__('admin.users_count')); ?></p>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <?php echo e(__('admin.add_user')); ?>

        </a>
    </div>

    
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       class="input ps-10" placeholder="<?php echo e(__('admin.search_users')); ?>">
            </div>
            <select name="role" class="input">
                <option value=""><?php echo e(__('admin.all_roles')); ?></option>
                <?php $__currentLoopData = ['super_admin'=>__('app.super_admin'),'school_admin'=>__('app.school_admin'),'counselor'=>__('app.counselor'),'teacher'=>__('app.teacher'),'parent'=>__('app.parent'),'student'=>__('app.student')]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($v); ?>" <?php echo e(request('role') === $v ? 'selected' : ''); ?>><?php echo e($l); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="school_id" class="input">
                <option value=""><?php echo e(__('admin.all_schools')); ?></option>
                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($school->id); ?>" <?php echo e(request('school_id') == $school->id ? 'selected' : ''); ?>><?php echo e($school->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
        <div class="flex gap-2 mt-3">
            <button type="submit" form="" onclick="this.closest('div').previousElementSibling.requestSubmit?.()" class="hidden"></button>
        </div>
    </div>

    
    <?php if($users->count()): ?>
    <div class="card overflow-hidden !p-0 animate-fade-up" style="animation-delay:.06s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface2 border-b border-bd">
                    <tr>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('admin.user')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('admin.role')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('admin.school')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('admin.account_status')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('admin.last_login')); ?></th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold"><?php echo e(__('app.actions')); ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bd">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-hover transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="<?php echo e($user->avatar_url); ?>" class="w-9 h-9 rounded-full object-cover" alt="">
                                <div class="min-w-0">
                                    <p class="font-bold text-main truncate"><?php echo e($user->name); ?></p>
                                    <p class="text-xs text-muted truncate"><?php echo e($user->email); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="badge-brand"><?php echo e(__('app.'.($user->roles->first()?->name ?? 'student'))); ?></span>
                        </td>
                        <td class="py-3 px-4 text-muted text-xs"><?php echo e($user->school?->name ?? '—'); ?></td>
                        <td class="py-3 px-4">
                            <span class="badge-<?php echo e($user->status === 'active' ? 'green' : 'red'); ?>">
                                <?php echo e(__('status.'.$user->status)); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4 text-faint text-xs"><?php echo e($user->last_login_at?->diffForHumans() ?? '—'); ?></td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                                    <?php echo e(__('app.edit')); ?>

                                </a>
                                <?php if($user->id !== auth()->id()): ?>
                                <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>"
                                      onsubmit="return confirm('<?php echo e(__('app.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-xs font-bold text-danger-600 hover:text-danger-500 transition">
                                        <?php echo e(__('app.delete')); ?>

                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-center"><?php echo e($users->withQueryString()->links()); ?></div>
    <?php else: ?>
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">👥</span>
        <p class="font-bold text-main mt-4 text-lg"><?php echo e(__('admin.no_users_found')); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/admin/users/index.blade.php ENDPATH**/ ?>