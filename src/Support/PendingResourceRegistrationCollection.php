<?php

namespace Webnuvola\Laravel\I18n\Support;

use Exception;
use Countable;
use BadMethodCallException;
use Illuminate\Routing\PendingResourceRegistration;

class PendingResourceRegistrationCollection implements Countable
{
    /**
     * The items contained in the collection.
     *
     * @var \Illuminate\Routing\PendingResourceRegistration[]
     */
    protected $pending = [];

    /**
     * Add a route to the collection
     *
     * @param \Illuminate\Routing\PendingResourceRegistration $pending
     * @return \Webnuvola\Laravel\I18n\Support\PendingResourceRegistrationCollection
     */
    public function push(PendingResourceRegistration $pending)
    {
        $this->pending[] = $pending;

        return $this;
    }

    /**
     * Returns the number of routes in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->pending);
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
            throw new Exception("Can't call method {$method} on an empty PendingResourceRegistrationCollection");
        }

        if (!method_exists($this->pending[0], $method)) {
            throw new BadMethodCallException("Method {$method} does not exist");
        }

        foreach ($this->pending as $route) {
            call_user_func_array([$route, $method], $arguments);
        }
    }
}
