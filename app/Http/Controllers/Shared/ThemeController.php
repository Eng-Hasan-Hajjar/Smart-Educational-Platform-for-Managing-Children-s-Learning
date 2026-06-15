<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * تبديل الوضع الليلي/النهاري وحفظه في الكوكيز + حساب المستخدم.
     */
    public function switch(Request $request, string $mode)
    {
        if (!in_array($mode, ['light', 'dark'])) {
            abort(400);
        }

        // حفظ التفضيل في كوكي لمدة سنة (يعمل حتى للزوار غير المسجلين)
        cookie()->queue('theme', $mode, 60 * 24 * 365);

        // إذا كان المستخدم مسجلاً، حفظ التفضيل في حسابه أيضاً
        if (auth()->check()) {
            auth()->user()->update(['theme' => $mode]);
        }

        // استجابة فارغة (الطلب يتم عبر fetch من JS بدون إعادة تحميل)
        return response()->json(['success' => true, 'theme' => $mode]);
    }
}