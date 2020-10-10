<?php

namespace Marshmallow\MultiLanguage\Traits;

use Illuminate\Support\Facades\App;
use Marshmallow\HelperFunctions\Facades\URL;
use Marshmallow\MultiLanguage\Models\Language;

trait TranslatableRoute
{
    public function localeRoute(Language $language = null)
    {
        return URL::buildFromArray(
            $this->getRouteParts($language)
        );
    }

    protected function getRouteParts(Language $language = null)
    {
        return array_filter([
            $this->getLocaleString($language),
            $this->routePrefix(),
            $this->getModelUrl($language),
        ]);
    }

    protected function getModelUrlField()
    {
        return $this->getRouteKeyName();
    }

    protected function getModelUrl(Language $language = null)
    {
        $url_column = $this->getModelUrlField();
        if ($language) {
            return $this->getTranslation($url_column, $language->code);
        }

        return $this->{$url_column};
    }

    protected function routePrefix()
    {
        return '';
    }

    protected function getLocaleString(Language $language = null)
    {
        if ($language) {
            return $language->code;
        }

        return App::getLocale();
    }
}
