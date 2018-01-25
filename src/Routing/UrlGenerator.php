<?php

namespace Webnuvola\Laravel\I18n\Routing;

use Illuminate\Http\Request;
use InvalidArgumentException;
use Webnuvola\Laravel\I18n\I18n;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator as IlluminateUrlGenerator;

class UrlGenerator extends IlluminateUrlGenerator
{
    /**
     * The I18n instance.
     *
     * @var \Webnuvola\Laravel\I18n\I18n
     */
    protected $i18n;

    /**
     * Create a new URL Generator instance.
     *
     * @param  \Illuminate\Routing\RouteCollection  $routes
     * @param  \Illuminate\Http\Request  $request
     * @param \Webnuvola\Laravel\I18n\I18n $i18n
     * @return void
     */
    public function __construct(RouteCollection $routes, Request $request, I18n $i18n)
    {
        $this->i18n = $i18n;

        parent::__construct($routes, $request);
    }

    /**
     * Generate an absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
     * @param  bool|null  $secure
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {
        if (!$this->isValidUrl($path)) {
            $path = $this->i18n->translateUri($path);
        }

        return parent::to($path, $extra, $secure);
    }

    /**
     * Get the URL to a named route.
     *
     * @param  string  $name
     * @param  mixed   $parameters
     * @param  bool  $absolute
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        if (is_null($route = $this->routes->getByName($name))) {
            $name = $this->i18n->getRegion() . ".{$name}";
        }

        return parent::route($name, $parameters, $absolute);
    }

    /**
     * Get the URL to a controller action.
     *
     * @param  string  $action
     * @param  mixed   $parameters
     * @param  bool    $absolute
     * @return string
     *
     * @throws \InvalidArgumentException
     * @throws \Illuminate\Routing\Exceptions\UrlGenerationException
     */
    public function action($action, $parameters = [], $absolute = true)
    {
        if (is_null($route = $this->routes->getByAction($action = $this->formatAction($action)))) {
            throw new InvalidArgumentException("Action {$action} not defined.");
        }

        $regionUriPath = $this->i18n->getRegionUriPath();

        foreach ($this->routes as $rt) {
            $actionName = $rt->getActionName();
            $prefix = ltrim($rt->getPrefix(), '/');

            if (
                $actionName === $action
                && $prefix === $regionUriPath
            ) {
                $route = $rt;
                break;
            }
        }

        return $this->toRoute($route, $parameters, $absolute);
    }
}
