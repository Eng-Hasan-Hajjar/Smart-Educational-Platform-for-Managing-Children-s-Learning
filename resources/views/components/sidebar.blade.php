@php
    $route = request()->route()->getName() ?? '';
    $is = fn($prefix) => str_starts_with($route, $prefix) ? 'active' : '';
@endphp

@if(auth()->user()->isSuperAdmin())
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $is('admin.dashboard') }}">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.dashboard') }}</span>
    </a>
    <a href="{{ route('admin.schools.index') }}" class="nav-link {{ $is('admin.schools') }}">
        <span class="nav-icon">🏫</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.schools') }}</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ $is('admin.users') }}">
        <span class="nav-icon">👥</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.users') }}</span>
    </a>

@elseif(auth()->user()->isSchoolAdmin())
    <a href="{{ route('school.dashboard') }}" class="nav-link {{ $is('school.dashboard') }}">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.dashboard') }}</span>
    </a>
    <a href="{{ route('school.teachers.index') }}" class="nav-link {{ $is('school.teachers') }}">
        <span class="nav-icon">👨‍🏫</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.teachers') }}</span>
    </a>
    <a href="{{ route('school.students.index') }}" class="nav-link {{ $is('school.students') }}">
        <span class="nav-icon">👨‍🎓</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.students') }}</span>
    </a>
    <a href="{{ route('school.classrooms.index') }}" class="nav-link {{ $is('school.classrooms') }}">
        <span class="nav-icon">🏛️</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.classrooms') }}</span>
    </a>
    <a href="{{ route('school.schedules.index') }}" class="nav-link {{ $is('school.schedules') }}">
        <span class="nav-icon">📅</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.schedule') }}</span>
    </a>
    <a href="{{ route('school.subjects.index') }}" class="nav-link {{ $is('school.subjects') }}">
        <span class="nav-icon">📚</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.subjects') }}</span>
    </a>
    <a href="{{ route('school.reports.index') }}" class="nav-link {{ $is('school.reports') }}">
        <span class="nav-icon">📊</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.reports') }}</span>
    </a>

@elseif(auth()->user()->isCounselor())
    <a href="{{ route('counselor.dashboard') }}" class="nav-link {{ $is('counselor.dashboard') }}">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.dashboard') }}</span>
    </a>
    <a href="{{ route('counselor.students.index') }}" class="nav-link {{ $is('counselor.students') }}">
        <span class="nav-icon">👨‍🎓</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.students') }}</span>
    </a>
    <a href="{{ route('counselor.reports.index') }}" class="nav-link {{ $is('counselor.reports') }}">
        <span class="nav-icon">📝</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.reports') }}</span>
    </a>

@elseif(auth()->user()->isTeacher())
    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ $is('teacher.dashboard') }}">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.dashboard') }}</span>
    </a>
    <a href="{{ route('teacher.units.index') }}" class="nav-link {{ $is('teacher.units') }}">
        <span class="nav-icon">📖</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.units') }}</span>
    </a>
    <a href="{{ route('teacher.lessons.index') }}" class="nav-link {{ $is('teacher.lessons') }}">
        <span class="nav-icon">🎬</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.lessons') }}</span>
    </a>
    <a href="{{ route('teacher.quizzes.index') }}" class="nav-link {{ $is('teacher.quizzes') }}">
        <span class="nav-icon">📝</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.quizzes') }}</span>
    </a>
    <a href="{{ route('teacher.assignments.index') }}" class="nav-link {{ $is('teacher.assignments') }}">
        <span class="nav-icon">📋</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.assignments') }}</span>
    </a>
    <a href="{{ route('teacher.attendance.index') }}" class="nav-link {{ $is('teacher.attendance') }}">
        <span class="nav-icon">✅</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.attendance') }}</span>
    </a>
    <a href="{{ route('teacher.students.index') }}" class="nav-link {{ $is('teacher.students') }}">
        <span class="nav-icon">👨‍🎓</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.students') }}</span>
    </a>
    <a href="{{ route('teacher.reports.index') }}" class="nav-link {{ $is('teacher.reports') }}">
        <span class="nav-icon">📊</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.reports') }}</span>
    </a>

@elseif(auth()->user()->isParent())
    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ $is('parent.dashboard') }}">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.dashboard') }}</span>
    </a>
    <a href="{{ route('parent.children.index') }}" class="nav-link {{ $is('parent.children') }}">
        <span class="nav-icon">👶</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.my_children') }}</span>
    </a>

@elseif(auth()->user()->isStudent())
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ $is('student.dashboard') }}">
        <span class="nav-icon">🏠</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.dashboard') }}</span>
    </a>
    <a href="{{ route('student.lessons.index') }}" class="nav-link {{ $is('student.lessons') }}">
        <span class="nav-icon">📚</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.my_lessons') }}</span>
    </a>
    <a href="{{ route('student.assignments.index') }}" class="nav-link {{ $is('student.assignments') }}">
        <span class="nav-icon">📋</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.my_assignments') }}</span>
    </a>
    <a href="{{ route('student.achievements.index') }}" class="nav-link {{ $is('student.achievements') }}">
        <span class="nav-icon">🏆</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.achievements') }}</span>
    </a>
@endif

{{-- مشترك بين الجميع --}}
<div class="pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,.08)">
    <a href="{{ route('announcements.index') }}" class="nav-link {{ $is('announcements') }}">
        <span class="nav-icon">📢</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.announcements') }}</span>
    </a>
    <a href="{{ route('messages.index') }}" class="nav-link {{ $is('messages') }}">
        <span class="nav-icon">✉️</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.messages') }}</span>
    </a>
    <a href="{{ route('notifications.index') }}" class="nav-link {{ $is('notifications') }}">
        <span class="nav-icon">🔔</span>
        <span x-show="sidebarOpen" x-cloak class="animate-fade">{{ __('app.notifications') }}</span>
    </a>
</div>