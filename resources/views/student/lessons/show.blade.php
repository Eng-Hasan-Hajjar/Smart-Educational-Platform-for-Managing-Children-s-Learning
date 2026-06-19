@extends('layouts.app')
@section('title', $lesson->title)
@section('page-title', $lesson->title)
@section('page-subtitle', ($lesson->unit->subject->name ?? '') . ' / ' . ($lesson->unit->title ?? ''))

@push('styles')
<style>
    .prose-content h1,.prose-content h2,.prose-content h3{font-weight:700;color:var(--text-main);margin:1rem 0 .5rem}
    .prose-content p{line-height:1.8;color:var(--text-main);margin:.5rem 0}
    .prose-content ul,.prose-content ol{padding-inline-start:1.5rem;margin:.5rem 0}
    .prose-content li{margin:.25rem 0;color:var(--text-main)}
    .prose-content strong{font-weight:700;color:var(--text-main)}
    .prose-content a{color:var(--brand-500);text-decoration:underline}
    .prose-content img{max-width:100%;border-radius:.75rem;margin:.5rem 0}
    .prose-content blockquote{border-inline-start:4px solid var(--brand-400);padding-inline-start:1rem;color:var(--text-muted);font-style:italic;margin:1rem 0}
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- ══════════ Breadcrumb ══════════ --}}
    <nav class="flex items-center flex-wrap gap-1.5 text-xs text-muted animate-fade-up">
        <a href="{{ route('student.lessons.index') }}" class="hover:text-brand-500 transition">
            📚 {{ __('student.my_lessons') }}
        </a>
        <span class="text-faint">/</span>
        <span>{{ $lesson->unit->subject->name ?? '' }}</span>
        <span class="text-faint">/</span>
        <span>{{ $lesson->unit->title ?? '' }}</span>
        <span class="text-faint">/</span>
        <span class="text-main font-medium">{{ $lesson->title }}</span>
    </nav>

    {{-- ══════════ Progress Banner ══════════ --}}
    <div class="card animate-fade-up" style="animation-delay:.04s">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-bold text-main flex items-center gap-2">
                @if($progress->is_completed)
                <span class="text-success-600">✅ {{ __('student.lesson_completed') }}</span>
                @else
                {{ __('student.your_progress') }}
                @endif
            </span>
            <span class="font-black text-brand-500 text-sm" id="progressLabel">
                {{ $progress->progress_percentage }}%
            </span>
        </div>
        <div class="progress-track !h-3">
            <div id="progressBar" class="progress-fill transition-all duration-700"
                 style="width: {{ $progress->progress_percentage }}%"></div>
        </div>
        @if($progress->time_spent_seconds > 0)
        <p class="text-xs text-faint mt-1.5">
            ⏱ {{ __('student.time_spent') }}: {{ gmdate('H:i:s', $progress->time_spent_seconds) }}
        </p>
        @endif
    </div>

    {{-- ══════════ Lesson Contents ══════════ --}}
    <div class="space-y-5">
        @foreach($lesson->contents as $content)
        <div class="card animate-fade-up" style="animation-delay:{{ .06 + $loop->index * .04 }}s">

            @if($content->title)
            <h3 class="font-bold text-main text-lg mb-4">{{ $content->title }}</h3>
            @endif

            {{-- Text --}}
            @if($content->isText())
            <div class="prose-content text-main leading-relaxed text-sm sm:text-base">
                {!! $content->body !!}
            </div>

            {{-- Video --}}
            @elseif($content->isVideo())
            @if($content->embed_html)
            <div class="rounded-2xl overflow-hidden aspect-video bg-black">
                {!! $content->embed_html !!}
            </div>
            @else
            <video controls class="w-full rounded-2xl max-h-96 bg-black"
                   preload="metadata"
                   onplay="window.__nourUpdateProgress(50)"
                   onended="window.__nourUpdateProgress(100)">
                <source src="{{ $content->media_url }}" type="video/mp4">
                <p class="text-muted text-sm p-4">{{ __('student.video_not_supported') }}</p>
            </video>
            @endif

            {{-- Audio --}}
            @elseif($content->isAudio())
            <div class="rounded-2xl p-5 border border-bd"
                 style="background: linear-gradient(135deg, var(--brand-50), var(--info-50))">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-500/15 text-brand-600 flex items-center justify-center text-2xl">
                        🎵
                    </div>
                    <div>
                        <p class="font-bold text-main">{{ $content->title ?? __('student.audio_recording') }}</p>
                        @if($content->duration_seconds)
                        <p class="text-xs text-muted">{{ gmdate('i:s', $content->duration_seconds) }}</p>
                        @endif
                    </div>
                </div>
                @if($content->embed_html)
                {!! $content->embed_html !!}
                @else
                <audio controls class="w-full"
                       onplay="window.__nourUpdateProgress(30)"
                       onended="window.__nourUpdateProgress(100)">
                    <source src="{{ $content->media_url }}">
                </audio>
                @endif
            </div>

            {{-- Image --}}
            @elseif($content->isImage())
            <div class="text-center">
                <img src="{{ $content->media_url }}"
                     class="max-h-96 rounded-2xl object-contain mx-auto bg-surface2 w-full"
                     alt="{{ $content->title }}">
            </div>

            {{-- Document --}}
            @elseif($content->type === 'document')
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-surface2 border border-bd">
                <div class="w-12 h-12 rounded-2xl bg-warning-50 text-warning-600 flex items-center justify-center text-2xl flex-shrink-0">
                    📄
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-main truncate">{{ $content->file_name ?? $content->title }}</p>
                    <p class="text-xs text-muted">{{ $content->file_size_human }}</p>
                </div>
                @if($content->is_downloadable)
                <a href="{{ $content->media_url }}" download class="btn-outline !py-2 text-xs flex-shrink-0">
                    ⬇️ {{ __('app.download') }}
                </a>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- ══════════ Audio Resources ══════════ --}}
    @if($lesson->audioResources->count())
    <div class="card animate-fade-up">
        <h3 class="font-bold text-main flex items-center gap-2 mb-4">
            <span class="w-9 h-9 rounded-xl bg-info-50 text-info-600 flex items-center justify-center">🔊</span>
            {{ __('student.audio_resources') }}
        </h3>
        <div class="space-y-4">
            @foreach($lesson->audioResources as $audio)
            <div class="rounded-2xl p-4 border border-bd" style="background: linear-gradient(135deg, var(--brand-50), var(--info-50))">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/15 text-brand-600 flex items-center justify-center text-xl">
                            🎙️
                        </div>
                        <div>
                            <p class="font-bold text-sm text-main">{{ $audio->title }}</p>
                            <p class="text-xs text-muted">{{ $audio->language === 'ar' ? '🇸🇦' : '🇬🇧' }}</p>
                        </div>
                    </div>
                    <span class="badge-brand">{{ $audio->category }}</span>
                </div>
                {!! $audio->player_html !!}
                @if($audio->show_transcript && $audio->transcript)
                <div class="mt-3 p-3 bg-surface/80 rounded-xl border border-bd">
                    <p class="text-xs font-bold text-muted mb-1">📝 {{ __('student.transcript') }}:</p>
                    <p class="text-sm text-main leading-relaxed">{{ $audio->transcript }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══════════ Linked Quizzes ══════════ --}}
    @if($lesson->quizzes->count())
    <div class="card animate-fade-up" style="background: linear-gradient(135deg, var(--brand-50), var(--info-50))">
        <h3 class="font-bold text-main flex items-center gap-2 mb-4">
            <span class="w-9 h-9 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center">📝</span>
            {{ __('student.lesson_quizzes') }}
        </h3>
        @foreach($lesson->quizzes as $quiz)
        <div class="flex items-center justify-between p-4 bg-surface rounded-2xl border border-bd mb-3 last:mb-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center text-xl">📝</div>
                <div>
                    <p class="font-bold text-main">{{ $quiz->title }}</p>
                    <p class="text-xs text-muted">
                        ❓ {{ $quiz->questions_count }} {{ __('teacher.questions') }}
                        · ⏱ {{ $quiz->duration_minutes ?? '—' }} {{ __('student.min') }}
                        · 🎯 {{ __('teacher.pass_marks') }}: {{ $quiz->pass_marks }}/{{ $quiz->total_marks }}
                    </p>
                </div>
            </div>
            @if($quiz->isAvailable())
            <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn-primary text-sm">
                {{ __('student.start_quiz') }} →
            </a>
            @else
            <span class="badge-gray">{{ __('student.quiz_unavailable') }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- ══════════ Navigation ══════════ --}}
    <div class="flex items-center justify-between gap-3 animate-fade-up">
        <div>
            @if($prevLesson)
            <a href="{{ route('student.lessons.show', $prevLesson) }}"
               class="btn-outline flex items-center gap-2">
                <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="hidden sm:inline">{{ $prevLesson->title }}</span>
                <span class="sm:hidden">{{ __('student.prev') }}</span>
            </a>
            @endif
        </div>

        <button onclick="window.__nourUpdateProgress(100)" class="btn-primary !px-8">
            ✅ {{ __('student.mark_complete') }}
        </button>

        <div>
            @if($nextLesson)
            <a href="{{ route('student.lessons.show', $nextLesson) }}"
               class="btn-primary flex items-center gap-2">
                <span class="hidden sm:inline">{{ $nextLesson->title }}</span>
                <span class="sm:hidden">{{ __('student.next') }}</span>
                <svg class="w-4 h-4 flip-rtl" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let __progressSent = {{ $progress->progress_percentage }};
    const __lessonId   = {{ $lesson->id }};

    window.__nourUpdateProgress = function(pct) {
        if (pct <= __progressSent) return;
        __progressSent = pct;

        const bar   = document.getElementById('progressBar');
        const label = document.getElementById('progressLabel');
        if (bar)   bar.style.width = pct + '%';
        if (label) label.textContent = pct + '%';

        fetch(`/student/lessons/${__lessonId}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ progress_percentage: pct, time_spent_seconds: 0 })
        })
        .then(r => r.json())
        .then(d => {
            if (d.is_completed) {
                const banner = document.querySelector('#progressLabel')?.closest('.card');
                if (banner) {
                    banner.classList.add('border-success-500/30', 'bg-success-50/40');
                }
            }
        });
    };

    // Scroll-based progress
    let __scrollTimer = null;
    window.addEventListener('scroll', () => {
        clearTimeout(__scrollTimer);
        __scrollTimer = setTimeout(() => {
            const scrolled = (window.scrollY / Math.max(
                document.body.scrollHeight - window.innerHeight, 1
            )) * 100;
            if (scrolled > __progressSent) {
                window.__nourUpdateProgress(Math.min(Math.round(scrolled), 90));
            }
        }, 500);
    });
</script>
@endpush