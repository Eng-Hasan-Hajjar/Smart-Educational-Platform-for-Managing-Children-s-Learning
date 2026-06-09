@extends('layouts.app')
@section('title','إدارة المستخدمين')
@section('page-title','👥 إدارة المستخدمين')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <p class="text-slate-500 text-sm">{{ $users->total() }} مستخدم</p>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">➕ مستخدم جديد</a>
    </div>

    <div class="card p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" class="input" placeholder="بحث بالاسم أو البريد...">
            <select name="role" class="input">
                <option value="">كل الأدوار</option>
                @foreach(['super_admin'=>'مدير النظام','school_admin'=>'مدير مدرسة','counselor'=>'موجه','teacher'=>'معلم','parent'=>'ولي أمر','student'=>'طالب'] as $val=>$label)
                <option value="{{ $val }}" {{ request('role')===$val?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">🔍 بحث</button>
        </form>
    </div>

    <div class="card overflow-hidden p-0">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">المستخدم</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الدور</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">المدرسة</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">الحالة</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">آخر دخول</th>
                    <th class="text-right py-3 px-4 text-slate-500 font-medium">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                            <div>
                                <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="badge-blue">{{ $user->roles->first()?->name ?? '—' }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-600 text-xs">{{ $user->school?->name ?? '—' }}</td>
                    <td class="py-3 px-4">
                        <span class="badge-{{ $user->status==='active'?'green':'red' }}">
                            {{ $user->status==='active'?'نشط':'غير نشط' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-slate-400 text-xs">{{ $user->last_login_at?->diffForHumans() ?? '—' }}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit',$user) }}" class="text-secondary hover:underline text-xs">تعديل</a>
                            <form method="POST" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('حذف هذا المستخدم؟')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline text-xs">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-slate-400">لا يوجد مستخدمون</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection