<?php

use Illuminate\Http\RedirectResponse;
use Webnuvola\Laravel\I18n\I18nRedirector;

if (! function_exists('i18n_url')) {
    /**
     * Generate a i18n url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @param  bool|null $secure
     * @return string
     */
    function i18n_url(string $path, mixed $parameters = [], ?bool $secure = null): string
    {
        return app('i18n.url')->to($path, $parameters, $secure);
    }
}

if (! function_exists('i18n_route')) {
    /**
     * Generate the URL to a named i18n route.
     *
     * @param  string $name
     * @param  mixed $parameters
     * @param  bool $absolute
     * @return string
     */
    function i18n_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        return app('i18n.url')->route($name, $parameters, $absolute);
    }
}

if (! function_exists('i18n_redirect')) {
    /**
     * Get an instance of the i18n redirector.
     *
     * @param  string|null $to
     * @param  int $status
     * @param  array $headers
     * @param  bool|null $secure
     * @return \Webnuvola\Laravel\I18n\I18nRedirector|\Illuminate\Http\RedirectResponse
     */
    function i18n_redirect(
        ?string $to = null,
        int $status = 302,
        array $headers = [],
        ?bool $secure = null,
    ): I18nRedirector|RedirectResponse {
        if (is_null($to)) {
            return app('i18n.redirect');
        }

        return app('i18n.redirect')->to($to, $status, $headers, $secure);
    }
}
