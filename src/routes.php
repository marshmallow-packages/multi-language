<?php

use Marshmallow\MultiLanguage\Models\Language;

Route::middleware(['web'])->get('locale/{language:code}', function (Language $language) {
    Session::put('locale', $language->code);
    return redirect()->back();
})->name('switch-language');