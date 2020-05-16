<?php

namespace Webnuvola\Laravel\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string to(string $path, $extra = [], bool $secure = null)
 * @method static string route(string $name, $parameters = [], bool $absolute = true)
 * @method static string signedRoute(string $name, array $parameters = [], \DateTimeInterface|\DateInterval|int $expiration = null, bool $absolute = true)
 * @method static string temporarySignedRoute(string $name, \DateTimeInterface|\DateInterval|int $expiration, array $parameters = [], bool $absolute = true)
 *
 * @see \Webnuvola\Laravel\I18n\I18nRoutes
 */
class I18nUrl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'i18n.url';
    }
}
