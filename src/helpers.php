<?php

use Illuminate\Http\RedirectResponse;
use Webnuvola\Laravel\I18n\I18nRedirector;
use Webnuvola\Laravel\I18n\I18nUrlGenerator;

if (! function_exists('i18n_url')) {
    /**
     * Generate an i18n URL for the application.
     *
     * @return ($path is null ? I18nUrlGenerator : string)
     */
    function i18n_url(?string $path = null, mixed $parameters = [], ?bool $secure = null): I18nUrlGenerator|string
    {
        if ($path === null) {
            return app(I18nUrlGenerator::class);
        }

        return app('i18n.url')->to($path, $parameters, $secure);
    }
}

if (! function_exists('i18n_route')) {
    /**
     * Generate the URL to a named i18n route.
     */
    function i18n_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        return app('i18n.url')->route($name, $parameters, $absolute);
    }
}

if (! function_exists('i18n_redirect')) {
    /**
     * Get an instance of the i18n redirector.
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
