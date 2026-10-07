<?php

namespace Webnuvola\Laravel\I18n;

use Closure;
use Illuminate\Routing\Router;

class I18nRoutes
{
    /**
     * I18nRoutes constructor.
     */
    public function __construct(
        protected I18n $i18n,
        protected Router $router,
    ) {}

    /**
     * Register i18n group routes.
     */
    public function group(Closure $routes): void
    {
        foreach ($this->i18n->getRegions() as $region) {
            $this->router
                ->name($region . '.')
                ->prefix($region)
                ->group($routes);
        }
    }
}
