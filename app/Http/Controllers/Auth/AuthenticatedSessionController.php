<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->redirectByRole(Auth::user()->role ?? 'student'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectByRole(string $role): string
    {
        return match ($role) {
            'super_admin' => '/admin/dashboard',
            'school_admin' => '/school/dashboard',
            'teacher' => '/teacher/dashboard',
            'counselor' => '/counselor/dashboard',
            'parent' => '/parent/dashboard',
            'student' => '/student/dashboard',
            default => '/',
        };
    }
}