<?php

namespace Webnuvola\Laravel\I18n;

use Illuminate\Support\ServiceProvider;
use Webnuvola\Laravel\I18n\Routing\Router;
use Illuminate\View\Compilers\BladeCompiler;
use Webnuvola\Laravel\I18n\Mixins\RouteMixin;
use Webnuvola\Laravel\I18n\Routing\UrlGenerator;
use Illuminate\Routing\Route as IlluminateRoute;

class I18nServiceProvider extends ServiceProvider
{
    /**
     * I18n config file path.
     * @var string
     */
    protected $configFile = __DIR__ . '/../config/i18n.php';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configFile => config_path('i18n.php'),
        ], 'config');

        IlluminateRoute::mixin(new RouteMixin);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configFile, 'i18n');

        $this->registerI18n();

        $this->registerRouter();

        $this->registerUrlGenerator();

        $this->registerBladeExtensions();
    }

    /**
     * Register the I18n service.
     *
     * @return void
     */
    public function registerI18n()
    {
        $this->app->singleton('i18n', function ($app) {
            return new I18n($app);
        });
    }

    /**
     * Register the I18n Router.
     *
     * @return void
     */
    public function registerRouter()
    {
        $this->app->singleton('i18n.router', function ($app) {
            return new Router($app);
        });
    }

    /**
     * Register the URL generator service.
     *
     * @return void
     */
    protected function registerUrlGenerator()
    {
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();

            // The URL generator needs the route collection that exists on the router.
            // Keep in mind this is an object, so we're passing by references here
            // and all the registered routes will be available to the generator.
            $app->instance('routes', $routes);

            $url = new UrlGenerator(
                $routes,
                $app->rebinding('request', $this->requestRebinder()),
                $app['i18n']
            );

            $url->setSessionResolver(function () {
                return $this->app['session'];
            });

            // If the route collection is "rebound", for example, when the routes stay
            // cached for the application, we will need to rebind the routes on the
            // URL generator instance so it has the latest version of the routes.
            $app->rebinding('routes', function ($app, $routes) {
                $app['url']->setRoutes($routes);
            });

            return $url;
        });
    }

    /**
     * Get the URL generator request rebinder.
     *
     * @return \Closure
     */
    protected function requestRebinder()
    {
        return function ($app, $request) {
            $app['url']->setRequest($request);
        };
    }

    /**
     * Register blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions()
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->if('ifregion', function ($region) {
                return $this->app['i18n']->getRegion() === $region;
            });

            $bladeCompiler->if('ifnotregion', function ($region) {
                return $this->app['i18n']->getRegion() !== $region;
            });

            $bladeCompiler->if('iflanguage', function ($language) {
                return $this->app['i18n']->getLanguage() === $language;
            });

            $bladeCompiler->if('ifnotlanguage', function ($language) {
                return $this->app['i18n']->getLanguage() !== $language;
            });

            $bladeCompiler->if('ifcountry', function ($country) {
                return $this->app['i18n']->getCountry() === $country;
            });

            $bladeCompiler->if('ifnotcountry', function ($country) {
                return $this->app['i18n']->getCountry() !== $country;
            });
        });
    }
}
