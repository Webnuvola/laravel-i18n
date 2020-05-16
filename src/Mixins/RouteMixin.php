<?php

namespace Webnuvola\Laravel\I18n\Mixins;

use Illuminate\Support\Arr;

class RouteMixin
{
    /**
     * Get route region.
     */
    public function getRegion()
    {
        return function () {
            $segments = explode('/', $this->uri());
            $segments = array_values(array_filter($segments, static function ($value) {
                return $value !== '';
            }));

            $region = Arr::get($segments, 0);

            return app('i18n')->isValidRegion($region)
                ? $region
                : null;
        };
    }
}
