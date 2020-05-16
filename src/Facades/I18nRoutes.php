<?php

namespace Webnuvola\Laravel\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void group(\Closure $routes)
 *
 * @see \Webnuvola\Laravel\I18n\I18nRoutes
 */
class I18nRoutes extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'i18n.routes';
    }
}
