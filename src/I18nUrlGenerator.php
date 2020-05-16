<?php

namespace Webnuvola\Laravel\I18n;

class I18nUrlGenerator
{
    /**
     * I18n instance.
     *
     * @var \Webnuvola\Laravel\I18n\I18n
     */
    protected $i18n;

    /**
     * I18nUrlGenerator constructor.
     *
     * @param \Webnuvola\Laravel\I18n\I18n $i18n
     */
    public function __construct(I18n $i18n)
    {
        $this->i18n = $i18n;
    }

    /**
     * Generate a i18n url for the application.
     *
     * @param  string $path
     * @param  mixed $parameters
     * @param  bool|null $secure
     * @return string
     */
    public function to(string $path, $parameters = [], $secure = null): string
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
    public function route(string $name, $parameters = [], $absolute = true): string
    {
        return app('url')->route($this->getI18nRouteName($name), $parameters, $absolute);
    }

    /**
     * Create a signed route URL for a named i18n route.
     *
     * @param  string $name
     * @param  array $parameters
     * @param  \DateTimeInterface|\DateInterval|int|null $expiration
     * @param  bool $absolute
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function signedRoute(string $name, $parameters = [], $expiration = null, $absolute = true): string
    {
        return app('url')->signedRoute($this->getI18nRouteName($name), $parameters, $expiration, $absolute);
    }

    /**
     * Create a temporary signed route URL for a named i18n route.
     *
     * @param  string $name
     * @param  \DateTimeInterface|\DateInterval|int $expiration
     * @param  array $parameters
     * @param  bool $absolute
     * @return string
     */
    public function temporarySignedRoute(string $name, $expiration, $parameters = [], $absolute = true): string
    {
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
