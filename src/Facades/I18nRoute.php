<?php

namespace Webnuvola\Laravel\I18n\Facades;

use Illuminate\Support\Facades\Facade;

class I18nRoute extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'i18n.router';
    }
}
