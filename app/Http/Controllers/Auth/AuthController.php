<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     * Display the login view.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.required' => trans('validation.required', ['attribute' => trans('app.email')]),
            'email.email' => trans('validation.email'),
            'email.exists' => trans('validation.exists'),
            'password.required' => trans('validation.required', ['attribute' => trans('app.password')]),
            'password.min' => trans('validation.min.string', ['attribute' => trans('app.password'), 'min' => 8]),
        ]);

        // محاولة تسجيل الدخول
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $request->boolean('remember'))) {
            $user = Auth::user();

            // التحقق من حالة الحساب (status enum: active/inactive/banned)
            if ($user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();

                return back()->withErrors([
                    'email' => trans('auth.account_inactive'),
                ]);
            }

            $request->session()->regenerate();

            // تحديث آخر تسجيل دخول
            $user->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ])->save();

            // توجيه المستخدم حسب دوره
            return redirect($user->getDashboardRoute())->with('success', trans('auth.login_success'));
        }

        // فشل تسجيل الدخول
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => trans('auth.failed'),
            ]);
    }

    /**
     * عرض صفحة التسجيل
     * Display the registration view.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * معالجة التسجيل
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'role' => [
                'required',
                Rule::in(['teacher', 'parent', 'student']), // الأدوار المسموحة للتسجيل الذاتي
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => trans('validation.required', ['attribute' => trans('app.name')]),
            'name.string' => trans('validation.string', ['attribute' => trans('app.name')]),
            'email.required' => trans('validation.required', ['attribute' => trans('app.email')]),
            'email.email' => trans('validation.email'),
            'email.unique' => trans('validation.unique', ['attribute' => trans('app.email')]),
            'phone.unique' => trans('validation.unique', ['attribute' => trans('app.phone')]),
            'role.required' => trans('validation.required', ['attribute' => trans('app.role')]),
            'role.in' => trans('validation.in', ['attribute' => trans('app.role')]),
            'password.required' => trans('validation.required', ['attribute' => trans('app.password')]),
            'password.min' => trans('validation.min.string', ['attribute' => trans('app.password'), 'min' => 8]),
            'password.confirmed' => trans('validation.confirmed', ['attribute' => trans('app.password')]),
        ]);

        // إنشاء المستخدم بالأعمدة الفعلية لجدول users
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
            'locale' => app()->getLocale(),
            'email_verified_at' => now(), // التحقق التلقائي في التطوير
        ]);

        // تعيين الدور عبر Spatie Permission
        // (firstOrCreate يضمن وجود الدور حتى قبل تشغيل الـ Seeder)
        $role = Role::firstOrCreate(
            ['name' => $validated['role'], 'guard_name' => 'web']
        );
        $user->assignRole($role);

        // تسجيل الدخول التلقائي
        Auth::login($user);

        $request->session()->regenerate();

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        // توجيه المستخدم حسب دوره
        return redirect($user->getDashboardRoute())->with('success', trans('auth.login_success'));
    }

    /**
     * تسجيل الخروج
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', trans('auth.logged_out'));
    }

    /**
     * عرض صفحة نسيان كلمة المرور
     * Display the forgot password view.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * معالجة طلب إعادة تعيين كلمة المرور
     * Handle a password reset link request.
     */
    public function sendResetLink(Request $request)
    {
        // التحقق من صحة البريد الإلكتروني
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ], [
            'email.required' => trans('validation.required', ['attribute' => trans('app.email')]),
            'email.email' => trans('validation.email'),
            'email.exists' => trans('validation.exists', ['attribute' => trans('app.email')]),
        ]);

        // إرسال رابط إعادة التعيين
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', trans($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => trans($status)]);
    }

    /**
     * عرض نموذج إعادة تعيين كلمة المرور
     * Display the reset password view.
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * معالجة إعادة تعيين كلمة المرور
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => trans('validation.required', ['attribute' => trans('app.email')]),
            'email.email' => trans('validation.email'),
            'email.exists' => trans('validation.exists', ['attribute' => trans('app.email')]),
            'password.required' => trans('validation.required', ['attribute' => trans('app.password')]),
            'password.min' => trans('validation.min.string', ['attribute' => trans('app.password'), 'min' => 8]),
            'password.confirmed' => trans('validation.confirmed', ['attribute' => trans('app.password')]),
            'token.required' => trans('validation.required', ['attribute' => 'Token']),
        ]);

        // إعادة تعيين كلمة المرور
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // إعادة التوجيه بناءً على النتيجة
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', trans($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => trans($status)]);
    }
}