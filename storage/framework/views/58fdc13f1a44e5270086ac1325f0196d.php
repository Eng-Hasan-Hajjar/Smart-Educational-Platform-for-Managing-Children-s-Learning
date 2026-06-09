

<?php $__env->startSection('title', 'منصة سارة التعليمية'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative overflow-hidden bg-gradient-to-br from-cyan-50 via-white to-violet-50 py-24">
    <div class="container-page grid items-center gap-12 lg:grid-cols-2">
        <div>
            <span class="rounded-full bg-white px-5 py-2 text-sm font-bold text-cyan-700 shadow">
                نظام إلكتروني ذكي لإدارة تعليم الأطفال
            </span>

            <h1 class="mt-7 text-4xl font-black leading-tight text-slate-950 md:text-6xl">
                تعليم تفاعلي ذكي وآمن للأطفال
            </h1>

            <p class="mt-6 text-lg leading-9 text-slate-600">
                منصة تعليمية تجمع بين الدروس التفاعلية، الاختبارات، الواجبات، متابعة الأداء،
                الإشعارات، ولوحات تحكم مخصصة للطالب والمعلم وولي الأمر والإدارة.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="<?php echo e(route('login')); ?>" class="btn-primary">الدخول إلى المنصة</a>
                <a href="#features" class="btn-secondary">استكشف المميزات</a>
            </div>
        </div>

        <div class="rounded-[2rem] bg-white p-6 shadow-2xl shadow-cyan-100">
            <div class="rounded-[1.5rem] bg-gradient-to-br from-cyan-100 to-violet-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-cyan-700">لوحة الطالب</p>
                        <h2 class="text-2xl font-black">مرحبًا يا بطل!</h2>
                    </div>
                    <div class="grid h-16 w-16 place-items-center rounded-2xl bg-white text-4xl shadow">🎓</div>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-white p-5 shadow">
                        <p class="text-slate-500">دروس اليوم</p>
                        <p class="mt-2 text-4xl font-black">4</p>
                    </div>

                    <div class="rounded-3xl bg-white p-5 shadow">
                        <p class="text-slate-500">النقاط</p>
                        <p class="mt-2 text-4xl font-black text-amber-500">850</p>
                    </div>

                    <div class="rounded-3xl bg-white p-5 shadow sm:col-span-2">
                        <div class="mb-3 flex justify-between font-bold">
                            <span>تقدم الأسبوع</span>
                            <span>72%</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 w-[72%] rounded-full bg-cyan-500"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-3xl bg-slate-950 p-5 text-white">
                    <p class="text-cyan-200">الدرس التالي</p>
                    <h3 class="mt-2 text-xl font-black">تعلم الحروف بالصوت والصورة</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="bg-white py-20">
    <div class="container-page">
        <div class="mx-auto max-w-3xl text-center">
            <h2 class="text-3xl font-black md:text-5xl">كل أطراف العملية التعليمية في مكان واحد</h2>
            <p class="mt-5 leading-8 text-slate-600">
                المنصة تدعم الطالب، المعلم، ولي الأمر، الموجه، مدير المدرسة، ومدير النظام.
            </p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            <?php $__currentLoopData = [
                ['🧒','واجهة طالب ممتعة','دروس، اختبارات، نقاط، شارات، وتجربة بسيطة مناسبة للأطفال.'],
                ['👩‍🏫','أدوات المعلم','إدارة الدروس، رفع الوسائط، إنشاء الاختبارات والواجبات.'],
                ['👨‍👩‍👧','متابعة ولي الأمر','تقارير، حضور وغياب، نتائج، إشعارات، وتواصل مباشر.'],
                ['📊','تحليلات ذكية','مؤشرات تقدم، إنذار مبكر، توصيات تعليمية، وتقارير أداء.'],
                ['🎥','محتوى متعدد الوسائط','فيديو، صوت، صور، نصوص، ومواد تفاعلية.'],
                ['🔔','إشعارات ورسائل','تنبيهات داخلية ورسائل تربط المدرسة بالأسرة.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                    <div class="text-4xl"><?php echo e($feature[0]); ?></div>
                    <h3 class="mt-5 text-xl font-black"><?php echo e($feature[1]); ?></h3>
                    <p class="mt-3 leading-8 text-slate-600"><?php echo e($feature[2]); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\engya\Desktop\جامعة الشهباء 2026\سارة مشروع التخرج\smart_educational_platform\resources\views/home.blade.php ENDPATH**/ ?>