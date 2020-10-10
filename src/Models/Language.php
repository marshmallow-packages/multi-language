<?php

namespace Marshmallow\MultiLanguage\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * Build an array like ["nl" => "Nederlands"].
     * This is used by the Menu builders througout our packages.
     */
    public static function getLanguageArray(): array
    {
        $languages = Language::get()->pluck('label', 'code')->toArray();
        if (! $languages) {
            return [
                'nl' => 'Nederlands',
            ];
        }

        return $languages;
    }
}
