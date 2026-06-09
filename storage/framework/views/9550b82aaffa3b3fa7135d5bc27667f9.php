<?php $__env->startSection('title', 'منصة سارة التعليمية'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative overflow-hidden bg-gradient-to-br from-cyan-50 via-white to-violet-50 py-24">
    <div class="container-page grid items-center gap-16 lg:grid-cols-2">
        <div>
            <span class="inline-flex rounded-full border border-cyan-100 bg-white px-5 py-2 text-sm font-black text-cyan-700 shadow-sm">
                نظام إلكتروني ذكي لإدارة تعليم الأطفال
            </span>

            <h1 class="mt-7 text-4xl font-black leading-tight text-slate-950 md:text-6xl">
                تعليم تفاعلي ذكي وآمن للأطفال
            </h1>

            <p class="mt-6 max-w-xl text-lg leading-9 text-slate-600">
                منصة تعليمية تجمع بين الدروس التفاعلية، الاختبارات، الواجبات، متابعة الأداء،
                الإشعارات، ولوحات تحكم مخصصة للطالب والمعلم وولي الأمر والإدارة.
            </p>

            <div class="mt-9 flex flex-wrap gap-4">
                <?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn-primary">
                        ابدأ الآن بإنشاء حساب
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn-secondary">
                        لدي حساب بالفعل
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('dashboard.redirect')); ?>" class="btn-primary">
                        الانتقال إلى لوحة التحكم
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="rounded-[2rem] bg-white p-6 shadow-2xl shadow-cyan-100">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-cyan-100 to-violet-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-black text-cyan-700">لوحة الطالب</p>
                        <h2 class="text-2xl font-black text-slate-950">مرحبًا يا بطل!</h2>
                    </div>
                    <div class="grid h-16 w-16 place-items-center rounded-2xl bg-white text-4xl shadow">🎓</div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-white p-5 shadow">
                        <p class="font-bold text-slate-500">دروس اليوم</p>
                        <p class="mt-2 text-4xl font-black text-slate-950">4</p>
                    </div>

                    <div class="rounded-3xl bg-white p-5 shadow">
                        <p class="font-bold text-slate-500">النقاط</p>
                        <p class="mt-2 text-4xl font-black text-amber-500">850</p>
                    </div>

                    <div class="rounded-3xl bg-white p-5 shadow sm:col-span-2">
                        <div class="mb-3 flex justify-between font-black">
                            <span>تقدم الأسبوع</span>
                            <span>72%</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 w-[72%] rounded-full bg-cyan-500"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-3xl bg-slate-950 p-5 text-white">
                    <p class="font-bold text-cyan-200">الدرس التالي</p>
                    <h3 class="mt-2 text-xl font-black">تعلم الحروف بالصوت والصورة</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="bg-white py-24">
    <div class="container-page">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-3xl font-black text-slate-950 md:text-5xl">كل أطراف العملية التعليمية في مكان واحد</h2>
            <p class="mt-5 leading-8 text-slate-600">
                المنصة تدعم الطالب، المعلم، ولي الأمر، الموجه، مدير المدرسة، ومدير النظام.
            </p>
        </div>

        <div class="mt-14 grid gap-6 md:grid-cols-3">
            <?php $__currentLoopData = [
                ['🧒','واجهة طالب ممتعة','دروس، اختبارات، نقاط، شارات، وتجربة بسيطة مناسبة للأطفال.'],
                ['👩‍🏫','أدوات المعلم','إدارة الدروس، رفع الوسائط، إنشاء الاختبارات والواجبات.'],
                ['👨‍👩‍👧','متابعة ولي الأمر','تقارير، حضور وغياب، نتائج، إشعارات، وتواصل مباشر.'],
                ['📊','تحليلات ذكية','مؤشرات تقدم، إنذار مبكر، توصيات تعليمية، وتقارير أداء.'],
                ['🎥','محتوى متعدد الوسائط','فيديو، صوت، صور، نصوص، ومواد تفاعلية.'],
                ['🔔','إشعارات ورسائل','تنبيهات داخلية ورسائل تربط المدرسة بالأسرة.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-8 shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl">
                    <div class="text-4xl"><?php echo e($feature[0]); ?></div>
                    <h3 class="mt-5 text-xl font-black text-slate-950"><?php echo e($feature[1]); ?></h3>
                    <p class="mt-3 leading-8 text-slate-600"><?php echo e($feature[2]); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section id="roles" class="bg-slate-950 py-24 text-white">
    <div class="container-page">
        <div class="text-center">
            <h2 class="text-3xl font-black md:text-5xl">لوحة مخصصة لكل دور</h2>
            <p class="mt-5 text-slate-400">صلاحيات منظمة وتجربة مختلفة حسب نوع المستخدم.</p>
        </div>

        <div class="mt-14 grid gap-5 md:grid-cols-3">
            <?php $__currentLoopData = [
                ['مدير النظام', 'إدارة المدارس والمستخدمين والتقارير العامة.'],
                ['مدير المدرسة', 'إدارة المعلمين والطلاب والفصول والجداول.'],
                ['المعلم', 'رفع الدروس وإنشاء الاختبارات والواجبات.'],
                ['ولي الأمر', 'متابعة الأبناء والنتائج والحضور والرسائل.'],
                ['الطالب', 'دروس تفاعلية ونقاط وشارات واختبارات.'],
                ['الموجه التربوي', 'تقارير تربوية وإنذار مبكر ومتابعة الطلاب.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-3xl bg-white/10 p-7 backdrop-blur transition hover:bg-white/15">
                    <h3 class="text-xl font-black text-cyan-200"><?php echo e($role[0]); ?></h3>
                    <p class="mt-3 leading-8 text-slate-300"><?php echo e($role[1]); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/layouts/app.blade.php ENDPATH**/ ?>