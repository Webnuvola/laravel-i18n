<?php

namespace Webnuvola\Laravel\I18n\Mixins;

use Closure;
use Illuminate\Support\Arr;

class RouteMixin
{
    /**
     * Get route region.
     *
     * @return Closure
     */
    public function getRegion(): Closure
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
