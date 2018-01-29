<?php

namespace Webnuvola\Laravel\I18n\Routing;

use Illuminate\Contracts\Foundation\Application;

class PendingResourceRegistration
{
    /**
     * The I18n instance.
     *
     * @var \Webnuvola\Laravel\I18n\I18n
     */
    protected $i18n;

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The url generator instance.
     *
     * @var \Webnuvola\Laravel\I18n\Routing\UrlGenerator
     */
    protected $url;

    /**
     * The resource name.
     *
     * @var string
     */
    protected $name;

    /**
     * The resource controller.
     *
     * @var string
     */
    protected $controller;

    /**
     * The resource options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Force the region prefix.
     *
     * @var bool
     */
    protected $forceRegionPrefix = false;

    /**
     * New route pending registration.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return $this
     */
    public function __construct(Application $app, $name, $controller, array $options = [])
    {
        $this->i18n = $app['i18n'];
        $this->router = $app['router'];

        $this->name = $name;
        $this->controller = $controller;
        $this->options = $options;

        return $this;
    }

    /**
     * Set the methods the controller should apply to.
     *
     * @param  array|string|dynamic  $methods
     * @return $this
     */
    public function only($methods)
    {
        $this->options['only'] = is_array($methods) ? $methods : func_get_args();

        return $this;
    }

    /**
     * Set the methods the controller should exclude.
     *
     * @param  array|string|dynamic  $methods
     * @return $this
     */
    public function except($methods)
    {
        $this->options['except'] = is_array($methods) ? $methods : func_get_args();

        return $this;
    }

    /**
     * Set the route names for controller actions.
     *
     * @param  array|string  $names
     * @return $this
     */
    public function names($names)
    {
        $this->options['names'] = $names;

        return $this;
    }

    /**
     * Set the route name for a controller action.
     *
     * @param  string  $method
     * @param  string  $name
     * @return $this
     */
    public function name($method, $name)
    {
        $this->options['names'][$method] = $name;

        return $this;
    }

    /**
     * Override the route parameter names.
     *
     * @param  array|string  $parameters
     * @return $this
     */
    public function parameters($parameters)
    {
        $this->options['parameters'] = $parameters;

        return $this;
    }

    /**
     * Override a route parameter's name.
     *
     * @param  string  $previous
     * @param  string  $new
     * @return $this
     */
    public function parameter($previous, $new)
    {
        $this->options['parameters'][$previous] = $new;

        return $this;
    }

    /**
     * Set a middleware to the resource.
     *
     * @param  mixed  $middleware
     * @return $this
     */
    public function middleware($middleware)
    {
        $this->options['middleware'] = $middleware;

        return $this;
    }

    protected function getNames()
    {
        return array_merge([
            'index' => 'index',
            'create' => 'create',
            'store' => 'store',
            'show' => 'show',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
        ], $this->options['names'] ?? []);
    }

    /**
     * Returns resource name.
     *
     * @return string
     */
    protected function getResourceName()
    {
        $segments = explode('/', $this->name);
        return end($segments);
    }

    /**
     * Handle the object's destruction.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->options['names'] = $this->getNames();
        $name = $this->getResourceName();

        foreach ($this->i18n->getRegions() as $region) {
            $options = $this->options;

            $options['names'] = array_map(function ($value) use ($region, $name) {
                return "{$region}.{$name}.{$value}";
            }, $options['names']);

            $this->router->resource(
                $this->i18n->translateUri($name, $region),
                $this->controller,
                $options
            );
        }
    }
}
