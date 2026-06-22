<?php
    $route = request()->route()->getName() ?? '';
    $is = fn($prefix) => str_starts_with($route, $prefix) ? 'active' : '';
?>

<?php if(auth()->user()->isSuperAdmin()): ?>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e($is('admin.dashboard')); ?>">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.dashboard')); ?></span>
    </a>
    <a href="<?php echo e(route('admin.schools.index')); ?>" class="nav-link <?php echo e($is('admin.schools')); ?>">
        <span class="nav-icon">🏫</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.schools')); ?></span>
    </a>
    <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e($is('admin.users')); ?>">
        <span class="nav-icon">👥</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.users')); ?></span>
    </a>

<?php elseif(auth()->user()->isSchoolAdmin()): ?>
    <a href="<?php echo e(route('school.dashboard')); ?>" class="nav-link <?php echo e($is('school.dashboard')); ?>">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.dashboard')); ?></span>
    </a>
    <a href="<?php echo e(route('school.teachers.index')); ?>" class="nav-link <?php echo e($is('school.teachers')); ?>">
        <span class="nav-icon">👨‍🏫</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.teachers')); ?></span>
    </a>
    <a href="<?php echo e(route('school.students.index')); ?>" class="nav-link <?php echo e($is('school.students')); ?>">
        <span class="nav-icon">👨‍🎓</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.students')); ?></span>
    </a>
    <a href="<?php echo e(route('school.classrooms.index')); ?>" class="nav-link <?php echo e($is('school.classrooms')); ?>">
        <span class="nav-icon">🏛️</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.classrooms')); ?></span>
    </a>
    <a href="<?php echo e(route('school.schedules.index')); ?>" class="nav-link <?php echo e($is('school.schedules')); ?>">
        <span class="nav-icon">📅</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.schedule')); ?></span>
    </a>
    <a href="<?php echo e(route('school.subjects.index')); ?>" class="nav-link <?php echo e($is('school.subjects')); ?>">
        <span class="nav-icon">📚</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.subjects')); ?></span>
    </a>
    <a href="<?php echo e(route('school.reports.index')); ?>" class="nav-link <?php echo e($is('school.reports')); ?>">
        <span class="nav-icon">📊</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.reports')); ?></span>
    </a>

<?php elseif(auth()->user()->isCounselor()): ?>
    <a href="<?php echo e(route('counselor.dashboard')); ?>" class="nav-link <?php echo e($is('counselor.dashboard')); ?>">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.dashboard')); ?></span>
    </a>
    <a href="<?php echo e(route('counselor.students.index')); ?>" class="nav-link <?php echo e($is('counselor.students')); ?>">
        <span class="nav-icon">👨‍🎓</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.students')); ?></span>
    </a>
    <a href="<?php echo e(route('counselor.reports.index')); ?>" class="nav-link <?php echo e($is('counselor.reports')); ?>">
        <span class="nav-icon">📝</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.reports')); ?></span>
    </a>

<?php elseif(auth()->user()->isTeacher()): ?>
    <a href="<?php echo e(route('teacher.dashboard')); ?>" class="nav-link <?php echo e($is('teacher.dashboard')); ?>">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.dashboard')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.units.index')); ?>" class="nav-link <?php echo e($is('teacher.units')); ?>">
        <span class="nav-icon">📖</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.units')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.lessons.index')); ?>" class="nav-link <?php echo e($is('teacher.lessons')); ?>">
        <span class="nav-icon">🎬</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.lessons')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.quizzes.index')); ?>" class="nav-link <?php echo e($is('teacher.quizzes')); ?>">
        <span class="nav-icon">📝</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.quizzes')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.assignments.index')); ?>" class="nav-link <?php echo e($is('teacher.assignments')); ?>">
        <span class="nav-icon">📋</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.assignments')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.attendance.index')); ?>" class="nav-link <?php echo e($is('teacher.attendance')); ?>">
        <span class="nav-icon">✅</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.attendance')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.students.index')); ?>" class="nav-link <?php echo e($is('teacher.students')); ?>">
        <span class="nav-icon">👨‍🎓</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.students')); ?></span>
    </a>
    <a href="<?php echo e(route('teacher.reports.index')); ?>" class="nav-link <?php echo e($is('teacher.reports')); ?>">
        <span class="nav-icon">📊</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.reports')); ?></span>
    </a>

<?php elseif(auth()->user()->isParent()): ?>
    <a href="<?php echo e(route('parent.dashboard')); ?>" class="nav-link <?php echo e($is('parent.dashboard')); ?>">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.dashboard')); ?></span>
    </a>
    <a href="<?php echo e(route('parent.children.index')); ?>" class="nav-link <?php echo e($is('parent.children')); ?>">
        <span class="nav-icon">👶</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.my_children')); ?></span>
    </a>

<?php elseif(auth()->user()->isStudent()): ?>
    <a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link <?php echo e($is('student.dashboard')); ?>">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.dashboard')); ?></span>
    </a>
    <a href="<?php echo e(route('student.lessons.index')); ?>" class="nav-link <?php echo e($is('student.lessons')); ?>">
        <span class="nav-icon">📚</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.my_lessons')); ?></span>
    </a>
    <a href="<?php echo e(route('student.assignments.index')); ?>" class="nav-link <?php echo e($is('student.assignments')); ?>">
        <span class="nav-icon">📋</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.my_assignments')); ?></span>
    </a>
    <a href="<?php echo e(route('student.achievements.index')); ?>" class="nav-link <?php echo e($is('student.achievements')); ?>">
        <span class="nav-icon">🏆</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.achievements')); ?></span>
    </a>
<?php endif; ?>


<div class="pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,.08)">
    <a href="<?php echo e(route('announcements.index')); ?>" class="nav-link <?php echo e($is('announcements')); ?>">
        <span class="nav-icon">📢</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.announcements')); ?></span>
    </a>
    <a href="<?php echo e(route('messages.index')); ?>" class="nav-link <?php echo e($is('messages')); ?>">
        <span class="nav-icon">✉️</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.messages')); ?></span>
    </a>
    <a href="<?php echo e(route('notifications.index')); ?>" class="nav-link <?php echo e($is('notifications')); ?>">
        <span class="nav-icon">🔔</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade"><?php echo e(__('app.notifications')); ?></span>
    </a>
</div><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/components/sidebar.blade.php ENDPATH**/ ?>