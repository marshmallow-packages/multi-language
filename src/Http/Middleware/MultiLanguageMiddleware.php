<?php

namespace Marshmallow\MultiLanguage\Http\Middleware;

use App;
use Closure;
use Config;
use Marshmallow\HelperFunctions\Facades\URL;
use Session;

class MultiLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Don't use this middleware for Nova routes
         */
        if (URL::isNova($request)) {
            return $next($request);
        }

        $locale = Session::get('locale', Config::get('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}
