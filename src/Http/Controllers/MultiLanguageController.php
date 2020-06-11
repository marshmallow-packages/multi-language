<?php

namespace Marshmallow\MultiLanguage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Marshmallow\MultiLanguage\Models\Language;

class MultiLanguageController extends Controller
{
	public function switchLanguage(Language $language)
	{
		Session::put('locale', $language->code);
        return redirect()->back();
	}
}
