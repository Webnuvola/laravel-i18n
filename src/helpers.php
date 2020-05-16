<?php

use Illuminate\Routing\UrlGenerator;

if (! function_exists('i18n_route')) {
    /**
     * Generate the URL to a named i18n route.
     *
     * @param  string $name
     * @param  mixed $parameters
     * @param  bool $absolute
     * @return string
     */
    function i18n_route(string $name, $parameters = [], $absolute = true)
    {
        $i18nName = app('i18n')->getRegion().".{$name}";

        if (app('router')->has($i18nName)) {
            return app(UrlGenerator::class)->route($i18nName, $parameters, $absolute);
        }

        return app(UrlGenerator::class)->route($name, $parameters, $absolute);
    }
}

if (! function_exists('i18n_url')) {
    /**
     * Generate a i18n url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @param  bool|null $secure
     * @return string
     */
    function i18n_url(string $path, $parameters = [], $secure = null)
    {
        $path = app('i18n')->getRegion().'/'.ltrim($path, '/');

        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}
