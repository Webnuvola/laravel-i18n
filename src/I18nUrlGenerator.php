<?php

namespace Webnuvola\Laravel\I18n;

use DateInterval;
use DateTimeInterface;

class I18nUrlGenerator
{
    /**
     * I18nUrlGenerator constructor.
     *
     * @param \Webnuvola\Laravel\I18n\I18n $i18n
     */
    public function __construct(
        protected I18n $i18n,
    ) {}

    /**
     * Generate a i18n url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @param  bool|null $secure
     * @return string
     */
    public function to(string $path, mixed $parameters = [], ?bool $secure = null): string
    {
        $path = rtrim($this->i18n->getRegion().'/'.ltrim($path, '/'), '/');

        return app('url')->to($path, $parameters, $secure);
    }

    /**
     * Generate the URL to a named i18n route.
     *
     * @param  string $name
     * @param  mixed $parameters
     * @param  bool $absolute
     * @return string
     */
    public function route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        return app('url')->route($this->getI18nRouteName($name), $parameters, $absolute);
    }

    /**
     * Create a signed route URL for a named i18n route.
     *
     * @param  string $name
     * @param  mixed $parameters
     * @param  \DateTimeInterface|\DateInterval|int|null $expiration
     * @param  bool $absolute
     * @return string
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
     *
     * @param  string $name
     * @param  \DateTimeInterface|\DateInterval|int $expiration
     * @param  mixed $parameters
     * @param  bool $absolute
     * @return string
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
     *
     * @param  string $name
     * @return string
     */
    protected function getI18nRouteName(string $name): string
    {
        $i18nName = app('i18n')->getRegion().".{$name}";

        return app('router')->has($i18nName) ? $i18nName : $name;
    }
}
