<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Set the application locale.
     *
     * Sets the language/locale for the application based on user selection.
     * Validates the requested locale against available options, stores it in session,
     * and redirects back to the previous page. Used for multilingual support.
     *
     * @param  Request  $request  HTTP request
     * @param  string  $locale  Locale code to set (e.g., 'en', 'vi', 'ja')
     * @return \Illuminate\Http\RedirectResponse Redirects back to previous page
     */
    public function setLocale(Request $request, $locale)
    {
        // Validate locale
        if (! in_array($locale, array_keys(config('app.available_locales')))) {
            abort(404);
        }

        // Store locale in session
        Session::put('locale', $locale);

        // Redirect back to previous page
        return redirect()->back();
    }
}
