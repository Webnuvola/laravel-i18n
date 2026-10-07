<?php

namespace Webnuvola\Laravel\I18n;

use DateInterval;
use DateTimeInterface;

class I18nUrlGenerator
{
    /**
     * I18nUrlGenerator constructor.
     */
    public function __construct(
        protected I18n $i18n,
    ) {}

    /**
     * Generate a i18n url for the application.
     */
    public function to(string $path, mixed $parameters = [], ?bool $secure = null): string
    {
        $path = rtrim($this->i18n->getRegion() . '/' . ltrim($path, '/'), '/');

        return app('url')->to($path, $parameters, $secure);
    }

    /**
     * Generate the URL to a named i18n route.
     */
    public function route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        return app('url')->route($this->getI18nRouteName($name), $parameters, $absolute);
    }

    /**
     * Create a signed route URL for a named i18n route.
     *
     *
     * @throws \InvalidArgumentException
     */
    public function signedRoute(
        string $name,
        mixed $parameters = [],
        DateTimeInterface|DateInterval|int|null $expiration = null,
        bool $absolute = true,
    ): string {
        return app('url')->signedRoute($this->getI18nRouteName($name), $parameters, $expiration, $absolute);
    }

    /**
     * Create a temporary signed route URL for a named i18n route.
     */
    public function temporarySignedRoute(
        string $name,
        DateTimeInterface|DateInterval|int $expiration,
        mixed $parameters = [],
        bool $absolute = true,
    ): string {
        return $this->signedRoute($name, $parameters, $expiration, $absolute);
    }

    /**
     * Return route i18n name.
     */
    protected function getI18nRouteName(string $name): string
    {
        $i18nName = app('i18n')->getRegion() . ".{$name}";

        return app('router')->has($i18nName) ? $i18nName : $name;
    }
}
