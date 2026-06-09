<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        if (!in_array($locale, ['ar', 'en'])) {
            abort(400);
        }

        Session::put('locale', $locale);

        // Update user locale in DB if logged in
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        return redirect()->back()->withHeaders([
            // إعادة توجيه مع تغيير اتجاه الصفحة
        ]);
    }
}