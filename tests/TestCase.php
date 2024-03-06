<?php

namespace Webnuvola\Laravel\I18n\Test;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Webnuvola\Laravel\I18n\I18nServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('i18n.regions', ['it-it', 'en-us', 'es-us', 'en-gb']);
        $app['config']->set('i18n.default', 'it-it');
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            I18nServiceProvider::class,
        ];
    }
}
