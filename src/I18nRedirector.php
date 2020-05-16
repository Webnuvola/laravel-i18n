<?php

namespace Webnuvola\Laravel\I18n;

use Illuminate\Routing\Redirector;
use Illuminate\Support\Traits\ForwardsCalls;

class I18nRedirector
{
    use ForwardsCalls;

    /**
     * @var \Webnuvola\Laravel\I18n\I18nUrlGenerator
     */
    protected $i18nGenerator;

    /**
     * Illuminate Redirector instance.
     *
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirector;

    public function __construct(I18nUrlGenerator $i18nUrlGenerator, Redirector $redirector)
    {
        $this->i18nGenerator = $i18nUrlGenerator;
        $this->redirector = $redirector;
    }

    /**
     * Create a new redirect response to the i18n "home" route.
     *
     * @param  int $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function home($status = 302)
    {
        return $this->redirector->to($this->i18nGenerator->route('home'), $status);
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param  string $path
     * @param  int $status
     * @param  array $headers
     * @param  bool|null $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function to($path, $status = 302, $headers = [], $secure = null)
    {
        return $this->redirector->to($this->i18nGenerator->to($path, [], $secure), $status, $headers);
    }

    /**
     * Create a new redirect response to a named i18n route.
     *
     * @param  string $route
     * @param  mixed $parameters
     * @param  int $status
     * @param  array $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function route($route, $parameters = [], $status = 302, $headers = [])
    {
        return $this->redirector->to($this->i18nGenerator->route($route, $parameters), $status, $headers);
    }

    /**
     * Create a new redirect response to a signed named i18n route.
     *
     * @param  string $route
     * @param  mixed $parameters
     * @param  \DateTimeInterface|\DateInterval|int|null $expiration
     * @param  int $status
     * @param  array $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signedRoute($route, $parameters = [], $expiration = null, $status = 302, $headers = [])
    {
        return $this->redirector->to($this->i18nGenerator->signedRoute($route, $parameters, $expiration), $status, $headers);
    }

    /**
     * Create a new redirect response to a signed named i18n route.
     *
     * @param  string $route
     * @param  \DateTimeInterface|\DateInterval|int|null $expiration
     * @param  mixed $parameters
     * @param  int $status
     * @param  array $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function temporarySignedRoute($route, $expiration, $parameters = [], $status = 302, $headers = [])
    {
        return $this->redirector->to($this->i18nGenerator->temporarySignedRoute($route, $expiration, $parameters), $status, $headers);
    }

    /**
     * Get the URL generator instance.
     *
     * @return \Webnuvola\Laravel\I18n\I18nUrlGenerator
     */
    public function getI18nUrlGenerator()
    {
        return $this->i18nGenerator;
    }

    /**
     * Forward call to Illuminate Redirector.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->redirector, $method, $parameters);
    }
}
