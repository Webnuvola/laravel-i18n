<?php

namespace Webnuvola\Laravel\I18n;

use DateInterval;
use DateTimeInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Traits\ForwardsCalls;

class I18nRedirector
{
    use ForwardsCalls;

    /**
     * I18nRedirector constructor.
     */
    public function __construct(
        protected I18nUrlGenerator $i18nUrlGenerator,
        protected Redirector $redirector,
    ) {}

    /**
     * Create a new redirect response to the i18n "home" route.
     */
    public function home(int $status = 302): RedirectResponse
    {
        return $this->redirector->to($this->i18nUrlGenerator->route('home'), $status);
    }

    /**
     * Create a new redirect response to the given path.
     */
    public function to(string $path, int $status = 302, array $headers = [], ?bool $secure = null): RedirectResponse
    {
        return $this->redirector->to($this->i18nUrlGenerator->to($path, [], $secure), $status, $headers);
    }

    /**
     * Create a new redirect response to a named i18n route.
     */
    public function route(string $route, mixed $parameters = [], int $status = 302, array $headers = []): RedirectResponse
    {
        return $this->redirector->to($this->i18nUrlGenerator->route($route, $parameters), $status, $headers);
    }

    /**
     * Create a new redirect response to a signed named i18n route.
     */
    public function signedRoute(
        string $route,
        mixed $parameters = [],
        DateTimeInterface|DateInterval|int|null $expiration = null,
        int $status = 302,
        array $headers = [],
    ): RedirectResponse {
        return $this->redirector->to($this->i18nUrlGenerator->signedRoute($route, $parameters, $expiration), $status, $headers);
    }

    /**
     * Create a new redirect response to a signed named i18n route.
     */
    public function temporarySignedRoute(
        string $route,
        DateTimeInterface|DateInterval|int $expiration,
        mixed $parameters = [],
        int $status = 302,
        array $headers = [],
    ): RedirectResponse {
        return $this->redirector->to($this->i18nUrlGenerator->temporarySignedRoute($route, $expiration, $parameters), $status, $headers);
    }

    /**
     * Get the URL generator instance.
     */
    public function getI18nUrlGenerator(): I18nUrlGenerator
    {
        return $this->i18nUrlGenerator;
    }

    /**
     * Forward call to Illuminate Redirector.
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->forwardCallTo($this->redirector, $method, $parameters);
    }
}
