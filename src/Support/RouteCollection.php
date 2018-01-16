<?php

namespace Webnuvola\Laravel\I18n\Support;

use Exception;
use Countable;
use ArrayIterator;
use IteratorAggregate;
use BadMethodCallException;
use Illuminate\Routing\Route;

class RouteCollection implements Countable, IteratorAggregate
{
    /**
     * The items contained in the collection.
     *
     * @var \Illuminate\Routing\Route[]
     */
    protected $routes = [];

    /**
     * Add a route to the collection
     *
     * @param \Illuminate\Routing\Route $route
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function push(Route $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->routes);
    }

    /**
     * Returns the number of routes in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->routes);
    }

    /**
     * Adds localized names to routes.
     *
     * @param string $name
     * @return \Webnuvola\Laravel\I18n\Support\RouteCollection
     */
    public function name($name)
    {
        foreach ($this->routes as $route) {
            $route->name($route->getRegion() . ".{$name}");
        }

        return $this;
    }

    /**
     * Call method on every route in the collection.
     *
     * @param string $method
     * @param array $arguments
     *
     * @throws Exception
     * @throws BadMethodCallException
     */
    public function __call($method, $arguments)
    {
        if ($this->count() < 1) {
            throw new Exception("Can't call method {$method} on an empty RouteCollection");
        }

        if (!method_exists($this->routes[0], $method)) {
            throw new BadMethodCallException("Method {$method} does not exist");
        }

        foreach ($this->routes as $route) {
            call_user_func_array([$route, $method], $arguments);
        }
    }
}
