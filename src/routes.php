<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])
    ->get(
        'locale/{language:code}',
        '\Marshmallow\MultiLanguage\Http\Controllers\MultiLanguageController@switchLanguage'
    )->name('switch-language');
