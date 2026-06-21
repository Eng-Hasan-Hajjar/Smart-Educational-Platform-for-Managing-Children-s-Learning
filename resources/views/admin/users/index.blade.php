@extends('layouts.app')
@section('title', __('app.users'))
@section('page-title', __('app.users'))
@section('page-subtitle', __('admin.users_subtitle'))

@section('content')
<div class="space-y-5">

    {{-- ══════════ Topbar ══════════ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fade-up">
        <p class="text-muted text-sm">{{ $users->total() }} {{ __('admin.users_count') }}</p>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('admin.add_user') }}
        </a>
    </div>

    {{-- ══════════ Filters ══════════ --}}
    <div class="card !p-4 animate-fade-up" style="animation-delay:.04s">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2">
                <span class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-faint pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="input ps-10" placeholder="{{ __('admin.search_users') }}">
            </div>
            <select name="role" class="input">
                <option value="">{{ __('admin.all_roles') }}</option>
                @foreach(['super_admin'=>__('app.super_admin'),'school_admin'=>__('app.school_admin'),'counselor'=>__('app.counselor'),'teacher'=>__('app.teacher'),'parent'=>__('app.parent'),'student'=>__('app.student')] as $v=>$l)
                <option value="{{ $v }}" {{ request('role') === $v ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <select name="school_id" class="input">
                <option value="">{{ __('admin.all_schools') }}</option>
                @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
        </form>
        <div class="flex gap-2 mt-3">
            <button type="submit" form="" onclick="this.closest('div').previousElementSibling.requestSubmit?.()" class="hidden"></button>
        </div>
    </div>

    {{-- ══════════ Users Table ══════════ --}}
    @if($users->count())
    <div class="card overflow-hidden !p-0 animate-fade-up" style="animation-delay:.06s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface2 border-b border-bd">
                    <tr>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('admin.user') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('admin.role') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('admin.school') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('admin.account_status') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('admin.last_login') }}</th>
                        <th class="text-start py-3.5 px-4 text-muted font-semibold">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-bd">
                    @foreach($users as $user)
                    <tr class="hover:bg-hover transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                                <div class="min-w-0">
                                    <p class="font-bold text-main truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-muted truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="badge-brand">{{ __('app.'.($user->roles->first()?->name ?? 'student')) }}</span>
                        </td>
                        <td class="py-3 px-4 text-muted text-xs">{{ $user->school?->name ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="badge-{{ $user->status === 'active' ? 'green' : 'red' }}">
                                {{ __('status.'.$user->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-faint text-xs">{{ $user->last_login_at?->diffForHumans() ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-xs font-bold text-brand-500 hover:text-brand-700 transition">
                                    {{ __('app.edit') }}
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold text-danger-600 hover:text-danger-500 transition">
                                        {{ __('app.delete') }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-center">{{ $users->withQueryString()->links() }}</div>
    @else
    <div class="card text-center py-16 animate-fade">
        <span class="text-6xl animate-float inline-block">👥</span>
        <p class="font-bold text-main mt-4 text-lg">{{ __('admin.no_users_found') }}</p>
    </div>
    @endif
</div>
@endsection