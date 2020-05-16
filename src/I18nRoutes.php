<?php

namespace Webnuvola\Laravel\I18n;

use Closure;
use Illuminate\Routing\Router;

class I18nRoutes
{
    /**
     * I18n instance.
     *
     * @var \Webnuvola\Laravel\I18n\I18n
     */
    protected $i18n;

    /**
     * Router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * I18nRoutes constructor.
     *
     * @param \Webnuvola\Laravel\I18n\I18n $i18n
     * @param \Illuminate\Routing\Router $router
     */
    public function __construct(I18n $i18n, Router $router)
    {
        $this->i18n = $i18n;
        $this->router = $router;
    }

    /**
     * Register i18n group routes.
     *
     * @param \Closure $routes
     * @return void
     */
    public function group(Closure $routes): void
    {
        foreach ($this->i18n->getRegions() as $region) {
            $this->router
                ->name($region.'.')
                ->prefix($region)
                ->group($routes);
        }
    }
}
