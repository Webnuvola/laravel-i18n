<?php

namespace Webnuvola\Laravel\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\RedirectResponse home(int $status = 302)
 * @method static \Illuminate\Http\RedirectResponse back(int $status = 302, array $headers = [], $fallback = false)
 * @method static \Illuminate\Http\RedirectResponse refresh(int $status = 302, array $headers = [])
 * @method static \Illuminate\Http\RedirectResponse guest(string $path, int $status = 302, array $headers = [], bool $secure = null)
 * @method static \Illuminate\Http\RedirectResponse intended(string $default = '/', int $status = 302, array $headers = [], bool $secure = null)
 * @method static \Illuminate\Http\RedirectResponse to(string $path, int $status = 302, array $headers = [], bool $secure = null)
 * @method static \Illuminate\Http\RedirectResponse away(string $path, int $status = 302, array $headers = [])
 * @method static \Illuminate\Http\RedirectResponse secure(string $path, int $status = 302, array $headers = [])
 * @method static \Illuminate\Http\RedirectResponse route(string $route, array $parameters = [], int $status = 302, array $headers = [])
 * @method static \Illuminate\Http\RedirectResponse signedRoute(string $name, array $parameters = [], \DateTimeInterface|\DateInterval|int $expiration = null, int $status = 302, array $headers = [])
 * @method static \Illuminate\Http\RedirectResponse temporarySignedRoute(string $name, \DateTimeInterface|\DateInterval|int $expiration, array $parameters = [], int $status = 302, array $headers = [])
 * @method static \Illuminate\Routing\UrlGenerator getUrlGenerator()
 * @method static \Webnuvola\Laravel\I18n\I18nUrlGenerator getI18nUrlGenerator()
 * @method static void setSession(\Illuminate\Session\Store $session)
 *
 * @see \Illuminate\Routing\Redirector
 */
class I18nRedirect extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'i18n.redirect';
    }
}
