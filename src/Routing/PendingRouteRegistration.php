<?php

namespace Webnuvola\Laravel\I18n\Routing;

use Illuminate\Contracts\Foundation\Application;

class PendingRouteRegistration
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
     * Route verbs.
     *
     * @var array|string
     */
    protected $methods;

    /**
     * Route uri.
     *
     * @var string
     */
    protected $uri;

    /**
     * Route action.
     *
     * @var string
     */
    protected $action;

    /**
     * Route name.
     *
     * @var string
     */
    protected $name;

    /**
     * Route where array.
     *
     * @var array
     */
    protected $wheres = [];

    /**
     * Route prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Route defaults array.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Contains the destination url when the route is a redirect.
     *
     * @var string
     */
    protected $redirect;

    /**
     * Contains the redirect status.
     *
     * @var int
     */
    protected $status;

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
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return $this
     */
    public function __construct(Application $app, $methods, $uri, $action)
    {
        $this->i18n = $app['i18n'];
        $this->router = $app['router'];
        $this->url = $app['url'];

        $this->methods = $methods;
        $this->uri = $uri;
        $this->action = $action;

        return $this;
    }

    /**
     * Add or change the route name.
     *
     * @param  string  $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set a regular expression requirement on the route.
     *
     * @param  array|string  $name
     * @param  string  $expression
     * @return $this
     */
    public function where($name, $expression = null)
    {
        $this->wheres = array_merge($this->wheres, is_array($name) ? $name : [$name => $expression]);
        return $this;
    }

    /**
     * Add a prefix to the route URI.
     *
     * @param  string  $prefix
     * @return $this
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Set a default value for the route.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function defaults($key, $value)
    {
        $this->defaults[$key] = $value;

        return $this;
    }

    /**
     * Create a redirect from one URI to another.
     *
     * @param  string  $destination
     * @param  int  $status
     * @return $this
     */
    public function redirect($destination, $status = 301)
    {
        $this->redirect = $destination;
        $this->status = $status;

        return $this;
    }

    /**
     * Force the region prefix.
     *
     * @return $this
     */
    public function forceRegionPrefix()
    {
        $this->forceRegionPrefix = true;

        return $this;
    }

    /**
     * Set the destination for redirects.
     *
     * @param  \Illuminate\Routing\Route $route
     * @param  string $region
     * @param  string $destination
     * @return void
     */
    protected function setRedirectDestination($route, $region, $destination, $status)
    {
        if (!$this->url->isValidUrl($destination)) {
            $destination = '/' . ltrim($this->i18n->translateUri($destination, $region), '/');
        }

        $route->defaults('destination', $destination);
        $route->defaults('status', $status);
    }

    /**
     * Handle the object's destruction.
     *
     * @return void
     */
    public function __destruct()
    {
        foreach ($this->i18n->getRegions() as $region) {
            $uri = $this->i18n->translateUri($this->uri, $region, [
                'forceRegionPrefix' => $this->forceRegionPrefix,
            ]);

            $route = $this->router->match($this->methods, $uri, $this->action)
                ->setRegion($region);

            if ($this->name) {
                $route->name("{$region}.{$this->name}");
            }

            if ($this->wheres) {
                $route->where($this->wheres);
            }

            if ($this->prefix) {
                $route->prefix($this->prefix);
            }

            foreach ($this->defaults as $key => $value) {
                $route->defaults($key, $value);
            }

            if ($this->redirect) {
                $this->setRedirectDestination($route, $region, $this->redirect, $this->status);
            }
        }
    }
}
