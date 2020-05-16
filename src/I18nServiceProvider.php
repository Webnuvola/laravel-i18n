<?php

namespace Webnuvola\Laravel\I18n;

use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Webnuvola\Laravel\I18n\Mixins\RouteMixin;

class I18nServiceProvider extends ServiceProvider
{
    /**
     * I18n config file path.
     * @var string
     */
    protected $configFile = __DIR__.'/../config/i18n.php';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
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
    public function register(): void
    {
        $this->mergeConfigFrom($this->configFile, 'i18n');

        $this->app->singleton('i18n', I18n::class);
        $this->app->bind('i18n.routes', I18nRoutes::class);

        $this->registerBladeExtensions();
    }

    /**
     * Register blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->if('ifregion', function ($regions) {
                return in_array($this->app['i18n']->getRegion(), Arr::wrap($regions), true);
            });

            $bladeCompiler->if('ifnotregion', function ($regions) {
                return ! in_array($this->app['i18n']->getRegion(), Arr::wrap($regions), true);
            });

            $bladeCompiler->if('iflanguage', function ($languages) {
                return in_array($this->app['i18n']->getLanguage(), Arr::wrap($languages), true);
            });

            $bladeCompiler->if('ifnotlanguage', function ($languages) {
                return ! in_array($this->app['i18n']->getLanguage(), Arr::wrap($languages), true);
            });

            $bladeCompiler->if('ifcountry', function ($countries) {
                return in_array($this->app['i18n']->getCountry(), Arr::wrap($countries), true);
            });

            $bladeCompiler->if('ifnotcountry', function ($countries) {
                return ! in_array($this->app['i18n']->getCountry(), Arr::wrap($countries), true);
            });
        });
    }
}
