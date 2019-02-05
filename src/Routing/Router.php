<?php

namespace Webnuvola\Laravel\I18n\Routing;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router as IlluminateRouter;
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
     * The app router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Create a new Router instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->router = $app['router'];
    }

    /**
     * Register a new GET route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function get($uri, $action)
    {
        return new PendingRouteRegistration($this->app, 'get', $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function post($uri, $action)
    {
        return new PendingRouteRegistration($this->app, 'post', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function put($uri, $action)
    {
        return new PendingRouteRegistration($this->app, 'put', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function patch($uri, $action)
    {
        return new PendingRouteRegistration($this->app, 'patch', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function delete($uri, $action)
    {
        return new PendingRouteRegistration($this->app, 'delete', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function options($uri, $action)
    {
        return new PendingRouteRegistration($this->app, 'options', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function any($uri, $action = null)
    {
        return new PendingRouteRegistration($this->app, IlluminateRouter::$verbs, $uri, $action);
    }

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function match($methods, $uri, $action)
    {
        return new PendingRouteRegistration($this->app, $methods, $uri, $action);
    }

    /**
     * Register an array of resource controllers.
     *
     * @param  array  $resources
     * @param  array  $options
     * @return void
     */
    public function resources(array $resources, array $options = [])
    {
        foreach ($resources as $name => $controller) {
            $this->resource($name, $controller, $options);
        }
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Webnuvola\Laravel\I18n\Routing\PendingResourceRegistration
     */
    public function resource($name, $controller, array $options = [])
    {
        return new PendingResourceRegistration($this->app, $name, $controller, $options);
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
     * Create a redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @param  int  $status
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function redirect($uri, $destination, $status = 302)
    {
        return (new PendingRouteRegistration(
            $this->app,
            IlluminateRouter::$verbs,
            $uri,
            '\Illuminate\Routing\RedirectController'
        ))->redirect($destination, $status);
    }

    /**
     * Create a permanent redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @return \Webnuvola\Laravel\I18n\Routing\PendingRouteRegistration
     */
    public function permanentRedirect($uri, $destination)
    {
        return $this->redirect($uri, $destination, 301);
    }
}
