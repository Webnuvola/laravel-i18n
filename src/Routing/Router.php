<?php

namespace Webnuvola\Laravel\I18n\Routing;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router as IlluminateRouter;
use Webnuvola\Laravel\I18n\Support\RouteCollection;
use Webnuvola\Laravel\I18n\Support\PendingResourceRegistrationCollection;

class Router
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The I18n instance.
     *
     * @var \Webnuvola\Laravel\I18n\I18n
     */
    protected $i18n;

    /**
     * The app router instance.
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
     * Create a new Router instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->i18n = $app['i18n'];
        $this->router =  $app['router'];
        $this->url = $app['url'];
    }

    /**
     * Create all the i18n routes and add them to router.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    protected function addRoute($methods, $uri, $action)
    {
        $routes = new RouteCollection;

        foreach ($this->i18n->getRegions() as $region) {
            $route = $this->router->match(
                $methods,
                $this->i18n->translateUri($uri, $region),
                $action
            )->setRegion($region);

            $routes->push($route);
        }

        return $routes;
    }

    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function get($uri, $action)
    {
        return $this->addRoute('get', $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function post($uri, $action)
    {
        return $this->addRoute('post', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function put($uri, $action)
    {
        return $this->addRoute('put', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function delete($uri, $action)
    {
        return $this->addRoute('delete', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function patch($uri, $action)
    {
        return $this->addRoute('patch', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function options($uri, $action)
    {
        return $this->addRoute('options', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function any($uri, $action = null)
    {
        return $this->addRoute(IlluminateRouter::$verbs, $uri, $action);
    }

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function match($methods, $uri, $action)
    {
        return $this->addRoute($methods, $uri, $action);
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Webnuvola\Laravel\I18n\Support\PendingResourceRegistrationCollection
     */
    public function resource($name, $controller, array $options = [])
    {
        $default = array_merge([
            'names' => [
                'index' => 'index',
                'create' => 'create',
                'store' => 'store',
                'show' => 'show',
                'edit' => 'edit',
                'update' => 'update',
                'destroy' => 'destroy',
            ],
        ], $options);

        $resourceName = $this->getResourceName($name);

        $resources = new PendingResourceRegistrationCollection;

        foreach ($this->i18n->getRegions() as $region) {
            $options = $default;
            $options['names'] = array_map(function ($value) use ($region, $resourceName) {
                return "{$region}.{$resourceName}.{$value}";
            }, $options['names']);

            $resources->push($this->router->resource(
                $this->i18n->translateUri($name, $region),
                $controller,
                $options
            ));
        }

        return $resources;
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure|string  $routes
     * @return void
     */
    public function group(array $attributes, $routes)
    {
        $this->router->group($attributes, $routes);
    }

    /**
     * Extract the name from a resource name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getResourceName($name)
    {
        $segments = explode('/', $name);

        return end($segments);
    }

    /**
     * Create a redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @param  int  $status
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function redirect($uri, $destination, $status = 301)
    {
        $routeCollection = $this->any($uri, '\Illuminate\Routing\RedirectController');

        foreach ($routeCollection as $route) {
            $dest = $this->url->isValidUrl($destination) ?
                $destination :
                '/' . ltrim($this->i18n->translateUri($destination, $route->getRegion()), '/');

            $route->defaults('destination', $dest)->defaults('status', $status);
        }

        return $routeCollection;
    }
}
