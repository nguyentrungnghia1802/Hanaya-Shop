<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Set the application locale.
     */
    public function setLocale(Request $request, $locale)
    {
        // Validate locale
        if (!in_array($locale, array_keys(config('app.available_locales')))) {
            abort(404);
        }
        
        // Store locale in session
        Session::put('locale', $locale);
        
        // Redirect back to previous page
        return redirect()->back();
    }
}
