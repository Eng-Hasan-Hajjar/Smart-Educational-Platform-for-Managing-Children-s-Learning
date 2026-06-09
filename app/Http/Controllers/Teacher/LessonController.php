<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonContent;
use App\Models\AudioResource;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:teacher']);
    }

    public function index(Request $request)
    {
        $teacher = Auth::user();

        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->with(['unit.subject', 'contents'])
            ->withCount('contents')
            ->when($request->search, fn($q) =>
                $q->where('title', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('teacher.lessons.index', compact('lessons'));
    }

    public function create()
    {
        $teacher = Auth::user();
        $units   = Unit::whereHas('subject', fn($q) =>
            $q->whereHas('teachers', fn($t) => $t->where('users.id', $teacher->id))
        )->with('subject')->orderBy('subject_id')->get();

        return view('teacher.lessons.create', compact('units'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unit_id'          => 'required|exists:units,id',
            'title'            => 'required|string|max:200',
            'description'      => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'order'            => 'nullable|integer|min:0',
            'is_free'          => 'boolean',
            'thumbnail'        => 'nullable|image|max:3072',
        ]);

        $data['teacher_id'] = Auth::id();
        $data['status']     = $request->input('status', 'draft');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('lessons/thumbnails', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $lesson = Lesson::create($data);

        return redirect()->route('teacher.lessons.edit', $lesson)
            ->with('success', __('app.save') . ' ✅');
    }

    public function edit(Lesson $lesson)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);

        $lesson->load(['contents' => fn($q) => $q->orderBy('order'), 'audioResources', 'quizzes', 'unit.subject']);

        $teacher = Auth::user();
        $subjects = $teacher->teachingSubjects()->get();

        return view('teacher.lessons.edit', compact('lesson', 'subjects'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);

        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'description'      => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'order'            => 'nullable|integer|min:0',
            'is_free'          => 'boolean',
            'thumbnail'        => 'nullable|image|max:3072',
        ]);

        $data['status'] = $request->input('status', $lesson->status);

        if ($data['status'] === 'published' && !$lesson->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            if ($lesson->thumbnail) Storage::disk('public')->delete($lesson->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('lessons/thumbnails', 'public');
        }

        $lesson->update($data);

        return back()->with('success', __('app.save') . ' ✅');
    }

    public function togglePublish(Lesson $lesson)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);

        $lesson->update([
            'status'       => $lesson->status === 'published' ? 'draft' : 'published',
            'published_at' => $lesson->status !== 'published' ? now() : $lesson->published_at,
        ]);

        return back()->with('success', 'تم تحديث حالة النشر ✅');
    }

    public function uploadContent(Request $request, Lesson $lesson)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);

        $data = $request->validate([
            'type'            => 'required|in:text,video,audio,image,document,interactive',
            'title'           => 'nullable|string|max:200',
            'body'            => 'nullable|string',
            'file'            => 'nullable|file|max:102400',
            'file_url'        => 'nullable|string',
            'source_type'     => 'required|in:upload,url,youtube,soundcloud,drive,vimeo',
            'order'           => 'nullable|integer|min:0',
            'is_downloadable' => 'boolean',
            'is_required'     => 'boolean',
            // Audio specific
            'category'        => 'nullable|in:phonics,pronunciation,story,song,explanation,exercise',
            'language'        => 'nullable|in:ar,en,fr',
            'transcript'      => 'nullable|string',
            'show_transcript' => 'boolean',
        ]);

        $filePath  = null;
        $fileName  = null;
        $fileSize  = null;
        $mimeType  = null;

        if ($request->hasFile('file')) {
            $file      = $request->file('file');
            $filePath  = $file->store('lessons/content', 'public');
            $fileName  = $file->getClientOriginalName();
            $fileSize  = $file->getSize();
            $mimeType  = $file->getMimeType();
        }

        $content = LessonContent::create([
            'lesson_id'       => $lesson->id,
            'type'            => $data['type'],
            'title'           => $data['title'] ?? null,
            'body'            => $data['body'] ?? null,
            'file_path'       => $filePath,
            'file_url'        => $data['file_url'] ?? null,
            'file_name'       => $fileName,
            'file_size'       => $fileSize,
            'mime_type'       => $mimeType,
            'source_type'     => $data['source_type'],
            'order'           => $data['order'] ?? 0,
            'is_downloadable' => $request->boolean('is_downloadable'),
            'is_required'     => $request->boolean('is_required', true),
        ]);

        // إذا كان نوعه صوت — أنشئ AudioResource أيضاً
        if ($data['type'] === 'audio') {
            AudioResource::create([
                'lesson_content_id' => $content->id,
                'lesson_id'         => $lesson->id,
                'title'             => $data['title'] ?? 'تسجيل صوتي',
                'category'          => $data['category'] ?? 'explanation',
                'language'          => $data['language'] ?? 'ar',
                'file_path'         => $filePath,
                'file_url'          => $data['file_url'] ?? null,
                'source_type'       => $data['source_type'],
                'transcript'        => $data['transcript'] ?? null,
                'show_transcript'   => $request->boolean('show_transcript'),
                'order'             => $data['order'] ?? 0,
                'is_active'         => true,
            ]);
        }

        return back()->with('success', 'تم إضافة المحتوى ✅');
    }

    public function deleteContent(Lesson $lesson, LessonContent $content)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);
        abort_if($content->lesson_id !== $lesson->id, 403);

        if ($content->file_path) Storage::disk('public')->delete($content->file_path);
        $content->audioResources()->delete();
        $content->delete();

        return back()->with('success', __('app.delete') . ' ✅');
    }

    public function destroy(Lesson $lesson)
    {
        abort_if($lesson->teacher_id !== Auth::id(), 403);
        $lesson->delete();

        return redirect()->route('teacher.lessons.index')
            ->with('success', __('app.delete') . ' ✅');
    }
}