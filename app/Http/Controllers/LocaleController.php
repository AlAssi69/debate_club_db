<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Supported locales.
     */
    private const SUPPORTED_LOCALES = ['en', 'ar'];

    /**
     * Switch the application locale and redirect back.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            return redirect()->back();
        }

        Session::put('locale', $locale);

        return redirect()->back();
    }
}
