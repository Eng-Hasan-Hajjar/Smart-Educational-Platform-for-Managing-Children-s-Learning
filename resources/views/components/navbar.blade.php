@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: "Cairo", system-ui, sans-serif;
    }
}

@layer components {
    .container-page {
        @apply mx-auto max-w-7xl px-4 sm:px-6 lg:px-8;
    }

    .btn-primary {
        @apply inline-flex items-center justify-center rounded-2xl bg-cyan-600 px-7 py-4 font-extrabold text-white shadow-xl shadow-cyan-200 transition duration-300 hover:-translate-y-1 hover:bg-cyan-700;
    }

    .btn-secondary {
        @apply inline-flex items-center justify-center rounded-2xl bg-white px-7 py-4 font-extrabold text-slate-800 shadow-xl shadow-slate-200 transition duration-300 hover:-translate-y-1;
    }

    .auth-input {
        @apply w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-bold text-slate-800 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100;
    }

    .auth-label {
        @apply mb-2 block text-sm font-extrabold text-slate-700;
    }

    .auth-card {
        @apply rounded-[2rem] border border-white/80 bg-white/90 p-8 shadow-2xl shadow-cyan-100 backdrop-blur-xl;
    }
}