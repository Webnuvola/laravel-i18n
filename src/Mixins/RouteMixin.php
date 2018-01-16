<?php

namespace Webnuvola\Laravel\I18n\Mixins;

class RouteMixin
{
    /**
     * Set route i18n region.
     */
    public function setRegion()
    {
        return function ($region) {
            if (!property_exists($this, 'i18n')) {
                $this->i18n = [];
            }

            $this->i18n['region'] = $region;

            return $this;
        };
    }

    /**
     * Get route region.
     */
    public function getRegion()
    {
        return function () {
            return $this->i18n['region'] ?? null;
        };
    }
}
