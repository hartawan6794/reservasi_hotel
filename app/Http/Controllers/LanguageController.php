<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switchLang($lang)
    {
        // Validate language
        if (!in_array($lang, ['id', 'en'])) {
            $lang = 'en';
        }
        
        // Store in session
        Session::put('locale', $lang);
        
        // Redirect back to previous page
        return Redirect::back();
    }
}

