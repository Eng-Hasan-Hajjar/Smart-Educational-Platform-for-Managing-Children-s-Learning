<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\{
    User, School, AcademicYear, AcademicLevel, Classroom,
    Subject, Unit, Lesson, TimeSlot, Schedule,
    TeacherProfile, StudentProfile, ParentProfile,
    GamificationPoint, Badge
};
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════
        //  1. الأدوار (Roles)
        // ══════════════════════════════════════════════════
        $this->command->info('🔐 Creating roles...');
        foreach (['super_admin', 'school_admin', 'counselor', 'teacher', 'parent', 'student'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ══════════════════════════════════════════════════
        //  2. المدرسة (School)
        // ══════════════════════════════════════════════════
        $this->command->info('🏫 Creating school...');
        $school = School::firstOrCreate(
            ['slug' => 'smart-academy'],
            [
                'name'                    => 'المدرسة النموذجية الذكية',
                'name_en'                 => 'Smart Model Academy',
                'slug'                    => 'smart-academy',
                'description'             => 'مدرسة نموذجية تعتمد أحدث الأساليب التعليمية والتقنيات الذكية',
                'email'                   => 'info@smart-academy.edu',
                'phone'                   => '0501234567',
                'address'                 => 'حلب - سوريا',
                'city'                    => 'حلب',
                'country'                 => 'سوريا',
                'status'                  => 'active',
                'subscription_plan'       => 'premium',
                'subscription_expires_at' => now()->addYear(),
                'max_students'            => 500,
                'max_teachers'            => 50,
            ]
        );

        // ══════════════════════════════════════════════════
        //  3. العام الدراسي
        //  columns: id, school_id, name, start_date, end_date, is_current, status
        // ══════════════════════════════════════════════════
        $this->command->info('📅 Creating academic year...');
        $academicYear = AcademicYear::firstOrCreate(
            ['school_id' => $school->id, 'is_current' => true],
            [
                'name'       => '2025-2026',
                'school_id'  => $school->id,
                'start_date' => '2025-09-01',
                'end_date'   => '2026-06-30',
                'is_current' => true,
                'status'     => 'active',
            ]
        );

        // ══════════════════════════════════════════════════
        //  4. المراحل الدراسية
        //  columns: id, school_id, name, name_en, order, color, icon, description, is_active
        // ══════════════════════════════════════════════════
        $this->command->info('📊 Creating academic levels...');
        $levelsData = [
            ['name' => 'الصف السابع',  'name_en' => 'Grade 7',  'order' => 7, 'color' => '#3B82F6', 'icon' => '7️⃣'],
            ['name' => 'الصف الثامن',  'name_en' => 'Grade 8',  'order' => 8, 'color' => '#8B5CF6', 'icon' => '8️⃣'],
            ['name' => 'الصف التاسع',  'name_en' => 'Grade 9',  'order' => 9, 'color' => '#EC4899', 'icon' => '9️⃣'],
        ];
        $createdLevels = [];
        foreach ($levelsData as $lv) {
            $createdLevels[] = AcademicLevel::firstOrCreate(
                ['school_id' => $school->id, 'order' => $lv['order']],
                $lv + ['school_id' => $school->id, 'is_active' => true]
            );
        }

        // ══════════════════════════════════════════════════
        //  5. الفصول الدراسية
        //  columns: id, school_id, academic_level_id, academic_year_id, name, section, capacity, room_number, is_active, description
        // ══════════════════════════════════════════════════
        $this->command->info('🏠 Creating classrooms...');
        $classrooms = [];
        foreach ($createdLevels as $level) {
            foreach (['أ' => 'A', 'ب' => 'B'] as $arSection => $enSection) {
                $classrooms[] = Classroom::firstOrCreate(
                    ['school_id' => $school->id, 'academic_level_id' => $level->id, 'section' => $enSection],
                    [
                        'school_id'         => $school->id,
                        'academic_level_id' => $level->id,
                        'academic_year_id'  => $academicYear->id,
                        'name'              => $level->name . ' - شعبة ' . $arSection,
                        'section'           => $enSection,
                        'capacity'          => 30,
                        'room_number'       => $level->order . '0' . ($enSection === 'A' ? '1' : '2'),
                        'is_active'         => true,
                    ]
                );
            }
        }

        // ══════════════════════════════════════════════════
        //  6. المواد الدراسية
        //  columns: id, school_id, academic_level_id, semester_id, name, name_en, code, description, icon, color, order, weekly_hours, is_active
        // ══════════════════════════════════════════════════
        // ══════════════════════════════════════════════════
        //  6. المواد الدراسية
        // ══════════════════════════════════════════════════
        $this->command->info('📚 Creating subjects...');
        $subjectsData = [
            ['name' => 'الرياضيات',        'name_en' => 'Mathematics', 'code' => 'MATH', 'icon' => '📐', 'color' => '#3B82F6', 'weekly_hours' => 5],
            ['name' => 'العلوم',           'name_en' => 'Science',     'code' => 'SCI',  'icon' => '🔬', 'color' => '#10B981', 'weekly_hours' => 4],
            ['name' => 'اللغة العربية',    'name_en' => 'Arabic',      'code' => 'ARB',  'icon' => '📖', 'color' => '#F59E0B', 'weekly_hours' => 5],
            ['name' => 'اللغة الإنجليزية', 'name_en' => 'English',     'code' => 'ENG',  'icon' => '🌍', 'color' => '#8B5CF6', 'weekly_hours' => 4],
            ['name' => 'الفيزياء',         'name_en' => 'Physics',     'code' => 'PHY',  'icon' => '⚡', 'color' => '#EF4444', 'weekly_hours' => 3],
        ];
        $subjects = [];
        foreach ($subjectsData as $sub) {
            $subjects[] = Subject::firstOrCreate(
                ['school_id' => $school->id, 'code' => $sub['code']],
                $sub + [
                    'school_id'        => $school->id,
                    'academic_level_id'=> $createdLevels[0]->id,
                    'is_active'        => true,
                    'order'            => 0,
                ]
            );
        }

        // ══════════════════════════════════════════════════
        //  7. الفترات الزمنية
        //  columns: id, school_id, name, start_time, end_time, is_break, order
        // ══════════════════════════════════════════════════
        $this->command->info('⏰ Creating time slots...');
        $timeSlotsData = [
            ['name' => 'الحصة الأولى',  'start_time' => '08:00', 'end_time' => '08:45', 'order' => 1, 'is_break' => false],
            ['name' => 'الحصة الثانية', 'start_time' => '08:50', 'end_time' => '09:35', 'order' => 2, 'is_break' => false],
            ['name' => 'الحصة الثالثة', 'start_time' => '09:40', 'end_time' => '10:25', 'order' => 3, 'is_break' => false],
            ['name' => 'استراحة',       'start_time' => '10:25', 'end_time' => '10:45', 'order' => 4, 'is_break' => true],
            ['name' => 'الحصة الرابعة', 'start_time' => '10:45', 'end_time' => '11:30', 'order' => 5, 'is_break' => false],
            ['name' => 'الحصة الخامسة', 'start_time' => '11:35', 'end_time' => '12:20', 'order' => 6, 'is_break' => false],
        ];
        $timeSlots = [];
        foreach ($timeSlotsData as $ts) {
            $slot = TimeSlot::firstOrCreate(
                ['school_id' => $school->id, 'order' => $ts['order']],
                $ts + ['school_id' => $school->id]
            );
            if (!$ts['is_break']) $timeSlots[] = $slot;
        }

        // ══════════════════════════════════════════════════
        //  8. المستخدمين (Users)
        // ══════════════════════════════════════════════════
        $this->command->info('👥 Creating demo users...');
        $password = Hash::make('password');

        // ─── Super Admin ───
        $superAdmin = User::firstOrCreate(['email' => 'admin@nour.com'], [
            'name' => 'مدير النظام', 'name_en' => 'Super Admin',
            'email' => 'admin@nour.com', 'password' => $password,
            'email_verified_at' => now(), 'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
        ]);
        $superAdmin->syncRoles('super_admin');

        // ─── School Admin ───
        $schoolAdmin = User::firstOrCreate(['email' => 'school@nour.com'], [
            'name' => 'مدير المدرسة', 'name_en' => 'School Admin',
            'email' => 'school@nour.com', 'phone' => '0509999001', 'password' => $password,
            'school_id' => $school->id, 'email_verified_at' => now(),
            'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
        ]);
        $schoolAdmin->syncRoles('school_admin');

        // ─── Teacher 1 (رياضيات) ───
        $teacher = User::firstOrCreate(['email' => 'teacher@nour.com'], [
            'name' => 'أحمد المعلم', 'name_en' => 'Ahmed Teacher',
            'email' => 'teacher@nour.com', 'phone' => '0509999002', 'password' => $password,
            'school_id' => $school->id, 'email_verified_at' => now(),
            'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
        ]);
        $teacher->syncRoles('teacher');

        // teacher_profiles: id, user_id, specialization, qualification, experience_years, bio
        TeacherProfile::firstOrCreate(['user_id' => $teacher->id], [
            'user_id'          => $teacher->id,
            'specialization'   => 'رياضيات',
            'qualification'    => 'ماجستير في الرياضيات التطبيقية',
            'experience_years' => 8,
            'bio'              => 'معلم رياضيات متمرس يستخدم أساليب تعليمية حديثة',
        ]);

        // ─── Teacher 2 (علوم) ───
        $teacher2 = User::firstOrCreate(['email' => 'teacher2@nour.com'], [
            'name' => 'سارة المعلمة', 'name_en' => 'Sara Teacher',
            'email' => 'teacher2@nour.com', 'phone' => '0509999003', 'password' => $password,
            'school_id' => $school->id, 'email_verified_at' => now(),
            'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
        ]);
        $teacher2->syncRoles('teacher');

        TeacherProfile::firstOrCreate(['user_id' => $teacher2->id], [
            'user_id'          => $teacher2->id,
            'specialization'   => 'علوم',
            'qualification'    => 'بكالوريوس في علوم الأحياء',
            'experience_years' => 5,
            'bio'              => 'معلمة علوم شغوفة بالتجارب المخبرية',
        ]);

        // ─── ربط المعلمين بالمواد والفصول ───
        $this->command->info('🔗 Linking teachers to subjects & classrooms...');
        $mathSubject    = $subjects[0];
        $scienceSubject = $subjects[1];

        foreach ($classrooms as $classroom) {
            DB::table('teacher_subject_classroom')->insertOrIgnore([
                'teacher_id' => $teacher->id, 'subject_id' => $mathSubject->id,
                'classroom_id' => $classroom->id, 'academic_year_id' => $academicYear->id,
            ]);
            DB::table('teacher_subject_classroom')->insertOrIgnore([
                'teacher_id' => $teacher2->id, 'subject_id' => $scienceSubject->id,
                'classroom_id' => $classroom->id, 'academic_year_id' => $academicYear->id,
            ]);
        }

        // ─── Students (5 طلاب) ───
        $this->command->info('🎓 Creating students...');
        $studentsData = [
            ['name' => 'محمد الطالب',   'name_en' => 'Mohammed Student',  'email' => 'student1@nour.com', 'phone' => '0509999010'],
            ['name' => 'فاطمة الطالبة', 'name_en' => 'Fatima Student',    'email' => 'student2@nour.com', 'phone' => '0509999011'],
            ['name' => 'عمر الطالب',    'name_en' => 'Omar Student',      'email' => 'student3@nour.com', 'phone' => '0509999012'],
            ['name' => 'نور الطالبة',   'name_en' => 'Nour Student',      'email' => 'student4@nour.com', 'phone' => '0509999013'],
            ['name' => 'ياسر الطالب',   'name_en' => 'Yasser Student',    'email' => 'student5@nour.com', 'phone' => '0509999014'],
        ];

        $studentUsers = [];
        foreach ($studentsData as $i => $data) {
            $student = User::firstOrCreate(['email' => $data['email']], $data + [
                'password' => $password, 'school_id' => $school->id,
                'email_verified_at' => now(), 'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
            ]);
            $student->syncRoles('student');
            $studentUsers[] = $student;

            // student_profiles: user_id, academic_level_id, student_number, enrollment_date, total_points, current_level
            StudentProfile::firstOrCreate(['user_id' => $student->id], [
                'user_id'           => $student->id,
                'academic_level_id' => $createdLevels[$i % count($createdLevels)]->id,
                'student_number'    => 'STU-' . str_pad($student->id, 4, '0', STR_PAD_LEFT),
                'enrollment_date'   => '2025-09-01',
                'total_points'      => rand(100, 500),
                'current_level'     => rand(1, 5),
                'gpa'               => rand(60, 99) / 10,
                
            ]);

            // ربط الطالب بفصل
          $classroom = $classrooms[$i % count($classrooms)];
$student->classrooms()->syncWithoutDetaching([
    $classroom->id => ['enrolled_at' => '2025-09-01', 'is_active' => true]
]);
        }

        // gamification_points: student_id, total_points, weekly_points, monthly_points, level, level_title
        $this->command->info('🏆 Creating gamification points...');
        $levelTitles = ['مبتدئ', 'متعلم', 'نشط', 'متميز', 'خبير'];
        foreach ($studentUsers as $student) {
            $lvl = rand(1, 5);
            GamificationPoint::firstOrCreate(['student_id' => $student->id], [
                'student_id'    => $student->id,
                'total_points'  => rand(100, 500),
                'weekly_points' => rand(10, 80),
                'monthly_points'=> rand(50, 200),
                'level'         => $lvl,
                'level_title'   => $levelTitles[$lvl - 1],
            ]);
        }

        // ─── Parent (ولي أمر) ───
        $parent = User::firstOrCreate(['email' => 'parent@nour.com'], [
            'name' => 'خالد ولي الأمر', 'name_en' => 'Khaled Parent',
            'email' => 'parent@nour.com', 'phone' => '0509999020', 'password' => $password,
            'school_id' => $school->id, 'email_verified_at' => now(),
            'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
        ]);
        $parent->syncRoles('parent');

        // parent_profiles: user_id, occupation, secondary_phone, relation_to_child, receive_sms, receive_email
        ParentProfile::firstOrCreate(['user_id' => $parent->id], [
            'user_id'          => $parent->id,
            'occupation'       => 'مهندس',
            'secondary_phone'  => '0509999021',
            'relation_to_child'=> 'أب',
            'receive_sms'      => true,
            'receive_email'    => true,
        ]);

        // ربط ولي الأمر بأبنائه
    $parent->children()->syncWithoutDetaching([
    $studentUsers[0]->id => ['relation' => 'أب', 'is_primary' => true],
    $studentUsers[1]->id => ['relation' => 'أب', 'is_primary' => false],
]);

        // ─── Counselor (الموجه التربوي) ───
        $counselor = User::firstOrCreate(['email' => 'counselor@nour.com'], [
            'name' => 'منى الموجهة', 'name_en' => 'Mona Counselor',
            'email' => 'counselor@nour.com', 'phone' => '0509999030', 'password' => $password,
            'school_id' => $school->id, 'email_verified_at' => now(),
            'status' => 'active', 'locale' => 'ar', 'theme' => 'light',
        ]);
        $counselor->syncRoles('counselor');

        // ══════════════════════════════════════════════════
        //  9. وحدات ودروس
        //  units: subject_id, teacher_id, title, title_en, description, order, is_published
        //  lessons: unit_id, teacher_id, title, description, order, duration_minutes, status
        // ══════════════════════════════════════════════════
        $this->command->info('📖 Creating sample units & lessons...');

        $mathUnit = Unit::firstOrCreate(
            ['subject_id' => $mathSubject->id, 'order' => 1],
            [
                'subject_id'   => $mathSubject->id,
                'teacher_id'   => $teacher->id,
                'title'        => 'الجبر والمعادلات',
                'title_en'     => 'Algebra & Equations',
                'description'  => 'وحدة تغطي أساسيات الجبر وحل المعادلات',
                'order'        => 1,
                'is_published' => true,
            ]
        );

        $sciUnit = Unit::firstOrCreate(
            ['subject_id' => $scienceSubject->id, 'order' => 1],
            [
                'subject_id'   => $scienceSubject->id,
                'teacher_id'   => $teacher2->id,
                'title'        => 'الخلية وتركيبها',
                'title_en'     => 'Cell Structure',
                'description'  => 'دراسة تركيب الخلية ووظائفها',
                'order'        => 1,
                'is_published' => true,
            ]
        );

        $lessonsData = [
            ['unit' => $mathUnit, 'teacher' => $teacher, 'title' => 'المعادلات الخطية',   'order' => 1, 'duration_minutes' => 45],
            ['unit' => $mathUnit, 'teacher' => $teacher, 'title' => 'المعادلات التربيعية', 'order' => 2, 'duration_minutes' => 45],
            ['unit' => $mathUnit, 'teacher' => $teacher, 'title' => 'جمل المعادلات',      'order' => 3, 'duration_minutes' => 40],
            ['unit' => $sciUnit,  'teacher' => $teacher2,'title' => 'مكونات الخلية',      'order' => 1, 'duration_minutes' => 40],
            ['unit' => $sciUnit,  'teacher' => $teacher2,'title' => 'الانقسام الخلوي',    'order' => 2, 'duration_minutes' => 45],
        ];

        foreach ($lessonsData as $ld) {
            Lesson::firstOrCreate(
                ['unit_id' => $ld['unit']->id, 'order' => $ld['order']],
                [
                    'unit_id'          => $ld['unit']->id,
                    'teacher_id'       => $ld['teacher']->id,
                    'title'            => $ld['title'],
                    'description'      => 'محتوى تجريبي - ' . $ld['title'],
                    'order'            => $ld['order'],
                    'duration_minutes' => $ld['duration_minutes'],
                    'status'           => 'published',
                    'published_at'     => now(),
                ]
            );
        }

        // ══════════════════════════════════════════════════
        //  10. جدول حصص
        //  schedules: classroom_id, subject_id, teacher_id, time_slot_id, academic_year_id, day_of_week, is_active
        // ══════════════════════════════════════════════════
        $this->command->info('📋 Creating sample schedules...');
        $todayDow = now()->dayOfWeek;

        foreach (array_slice($classrooms, 0, 2) as $ci => $classroom) {
            Schedule::firstOrCreate(
                ['classroom_id' => $classroom->id, 'teacher_id' => $teacher->id, 'day_of_week' => $todayDow, 'time_slot_id' => $timeSlots[$ci]->id],
                [
                    'classroom_id'    => $classroom->id,
                    'teacher_id'      => $teacher->id,
                    'subject_id'      => $mathSubject->id,
                    'day_of_week'     => $todayDow,
                    'time_slot_id'    => $timeSlots[$ci]->id,
                    'academic_year_id'=> $academicYear->id,
                    'is_active'       => true,
                ]
            );
        }

        // ══════════════════════════════════════════════════
        //  11. الشارات
        //  badges: name, description, icon, color, category, condition_type, condition_value, points_reward, is_active
        // ══════════════════════════════════════════════════
        $this->command->info('🏅 Creating badges...');
$badgesData = [
    ['name' => 'المتعلم النشط',  'description' => 'أكمل 10 دروس',            'icon' => '📖', 'color' => '#3B82F6', 'category' => 'academic',   'condition_type' => 'lessons_completed',    'condition_value' => 10, 'points_reward' => 50],
    ['name' => 'نجم الاختبارات', 'description' => 'احصل على 90% في 5 اختبارات','icon' => '⭐', 'color' => '#F59E0B', 'category' => 'academic',   'condition_type' => 'quiz_score',           'condition_value' => 5,  'points_reward' => 100],
    ['name' => 'الملتزم',        'description' => 'حضور 30 يوم متتالي',       'icon' => '🔥', 'color' => '#EF4444', 'category' => 'attendance', 'condition_type' => 'perfect_attendance',   'condition_value' => 30, 'points_reward' => 75],
    ['name' => 'المثابر',        'description' => 'سلّم 20 واجب',             'icon' => '💪', 'color' => '#10B981', 'category' => 'academic',   'condition_type' => 'assignments_done',    'condition_value' => 20, 'points_reward' => 60],
    ['name' => 'المتفوق',        'description' => 'اجمع 1000 نقطة',           'icon' => '🏆', 'color' => '#8B5CF6', 'category' => 'special',    'condition_type' => 'points_earned',       'condition_value' => 1000,'points_reward' => 200],
];

        foreach ($badgesData as $badge) {
            Badge::firstOrCreate(
                ['condition_type' => $badge['condition_type']],
                $badge + ['is_active' => true]
            );
        }

        // ══════════════════════════════════════════════════
        //  ✅ ملخص
        // ══════════════════════════════════════════════════
        $this->command->newLine();
        $this->command->info('══════════════════════════════════════════════════');
        $this->command->info('  ✅ تم تجهيز المنصة التعليمية الذكية بنجاح!');
        $this->command->info('══════════════════════════════════════════════════');
        $this->command->newLine();
        $this->command->table(
            ['الدور (Role)', 'البريد الإلكتروني (Email)', 'كلمة المرور'],
            [
                ['🔴 Super Admin',   'admin@nour.com',      'password'],
                ['🟠 School Admin',  'school@nour.com',     'password'],
                ['🟡 Counselor',     'counselor@nour.com',  'password'],
                ['🟢 Teacher 1',     'teacher@nour.com',    'password'],
                ['🟢 Teacher 2',     'teacher2@nour.com',   'password'],
                ['🔵 Parent',        'parent@nour.com',     'password'],
                ['🟣 Student 1',     'student1@nour.com',   'password'],
                ['🟣 Student 2',     'student2@nour.com',   'password'],
                ['🟣 Student 3',     'student3@nour.com',   'password'],
                ['🟣 Student 4',     'student4@nour.com',   'password'],
                ['🟣 Student 5',     'student5@nour.com',   'password'],
            ]
        );
    }
}