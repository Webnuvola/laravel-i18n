<?php

namespace Webnuvola\Laravel\I18n\Test;

class I18nUrlGeneratorTest extends TestCase
{
    /** @var \Webnuvola\Laravel\I18n\I18n */
    protected $i18n;

    /** @var \Webnuvola\Laravel\I18n\I18nUrlGenerator */
    protected $i18nUrlGenerator;

    /** @var \Illuminate\Routing\Router */
    protected $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->i18n = app('i18n');
        $this->i18nUrlGenerator = app('i18n.url');
        $this->router = app('router');

        $this->router->get('/')->name('home-redirect');
        $this->router->get('select-region')->name('select-region');

        app('i18n.routes')->group(function () {
            $this->router->get('/')->name('home');
            $this->router->get('page')->name('page');
            $this->router->get('products/{id}')->name('product.show');
        });

        app('url');
    }

    public function testTo()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', $this->i18nUrlGenerator->to('/'));
        $this->assertEquals('http://localhost/it-it/page', $this->i18nUrlGenerator->to('page'));
        $this->assertEquals('http://localhost/it-it/products/1', $this->i18nUrlGenerator->to('products', 1));

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us', $this->i18nUrlGenerator->to('/'));
        $this->assertEquals('http://localhost/en-us/page', $this->i18nUrlGenerator->to('page'));
        $this->assertEquals('http://localhost/en-us/products/1', $this->i18nUrlGenerator->to('products', 1));

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us', $this->i18nUrlGenerator->to('/'));
        $this->assertEquals('http://localhost/es-us/page', $this->i18nUrlGenerator->to('page'));
        $this->assertEquals('http://localhost/es-us/products/1', $this->i18nUrlGenerator->to('products', 1));

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb', $this->i18nUrlGenerator->to('/'));
        $this->assertEquals('http://localhost/en-gb/page', $this->i18nUrlGenerator->to('page'));
        $this->assertEquals('http://localhost/en-gb/products/1', $this->i18nUrlGenerator->to('products', 1));
    }

    public function testRoute()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', $this->i18nUrlGenerator->route('home'));
        $this->assertEquals('http://localhost/it-it/page', $this->i18nUrlGenerator->route('page'));
        $this->assertEquals('http://localhost/it-it/products/1', $this->i18nUrlGenerator->route('product.show', 1));

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us', $this->i18nUrlGenerator->route('home'));
        $this->assertEquals('http://localhost/en-us/page', $this->i18nUrlGenerator->route('page'));
        $this->assertEquals('http://localhost/en-us/products/1', $this->i18nUrlGenerator->route('product.show', 1));

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us', $this->i18nUrlGenerator->route('home'));
        $this->assertEquals('http://localhost/es-us/page', $this->i18nUrlGenerator->route('page'));
        $this->assertEquals('http://localhost/es-us/products/1', $this->i18nUrlGenerator->route('product.show', 1));

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb', $this->i18nUrlGenerator->route('home'));
        $this->assertEquals('http://localhost/en-gb/page', $this->i18nUrlGenerator->route('page'));
        $this->assertEquals('http://localhost/en-gb/products/1', $this->i18nUrlGenerator->route('product.show', 1));
    }

    public function testRouteFallback()
    {
        $this->assertEquals('http://localhost', $this->i18nUrlGenerator->route('home-redirect'));
        $this->assertEquals('http://localhost/select-region', $this->i18nUrlGenerator->route('select-region'));
        $this->assertEquals('http://localhost/it-it', $this->i18nUrlGenerator->route('it-it.home'));
        $this->assertEquals('http://localhost/en-us', $this->i18nUrlGenerator->route('en-us.home'));
        $this->assertEquals('http://localhost/es-us', $this->i18nUrlGenerator->route('es-us.home'));
        $this->assertEquals('http://localhost/en-gb', $this->i18nUrlGenerator->route('en-gb.home'));
    }

    public function testHelpers()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', i18n_url('/'));
        $this->assertEquals('http://localhost/it-it', i18n_route('home'));
        $this->assertEquals('http://localhost', i18n_route('home-redirect'));
    }
}
