<?php

namespace Webnuvola\Laravel\I18n\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Webnuvola\Laravel\I18n\I18nServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param  \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('i18n.regions', ['it-it', 'en-us', 'es-us', 'en-gb']);
        $app['config']->set('i18n.default', 'it-it');
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            I18nServiceProvider::class,
        ];
    }
}
