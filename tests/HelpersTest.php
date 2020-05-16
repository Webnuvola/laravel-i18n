<?php

namespace Webnuvola\Laravel\I18n\Test;

class HelpersTest extends TestCase
{
    /** @var \Webnuvola\Laravel\I18n\I18n */
    protected $i18n;

    /** @var \Illuminate\Routing\Router */
    protected $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->i18n = app('i18n');
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

    public function testI18nRouteHelper()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', i18n_route('home'));
        $this->assertEquals('http://localhost/it-it/page', i18n_route('page'));
        $this->assertEquals('http://localhost/it-it/products/1', i18n_route('product.show', 1));

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us', i18n_route('home'));
        $this->assertEquals('http://localhost/en-us/page', i18n_route('page'));
        $this->assertEquals('http://localhost/en-us/products/1', i18n_route('product.show', 1));

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us', i18n_route('home'));
        $this->assertEquals('http://localhost/es-us/page', i18n_route('page'));
        $this->assertEquals('http://localhost/es-us/products/1', i18n_route('product.show', 1));

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb', i18n_route('home'));
        $this->assertEquals('http://localhost/en-gb/page', i18n_route('page'));
        $this->assertEquals('http://localhost/en-gb/products/1', i18n_route('product.show', 1));
    }

    public function testI18nRouteHelperFallback()
    {
        $this->assertEquals('http://localhost', i18n_route('home-redirect'));
        $this->assertEquals('http://localhost/select-region', i18n_route('select-region'));
        $this->assertEquals('http://localhost/it-it', i18n_route('it-it.home'));
        $this->assertEquals('http://localhost/en-us', i18n_route('en-us.home'));
        $this->assertEquals('http://localhost/es-us', i18n_route('es-us.home'));
        $this->assertEquals('http://localhost/en-gb', i18n_route('en-gb.home'));
    }

    public function testI18nUrlHelper()
    {
        $this->i18n->setRegion('it-it');
        $this->assertEquals('http://localhost/it-it', i18n_url('/'));
        $this->assertEquals('http://localhost/it-it/page', i18n_url('page'));
        $this->assertEquals('http://localhost/it-it/products/1', i18n_url('products', 1));

        $this->i18n->setRegion('en-us');
        $this->assertEquals('http://localhost/en-us', i18n_url('/'));
        $this->assertEquals('http://localhost/en-us/page', i18n_url('page'));
        $this->assertEquals('http://localhost/en-us/products/1', i18n_url('products', 1));

        $this->i18n->setRegion('es-us');
        $this->assertEquals('http://localhost/es-us', i18n_url('/'));
        $this->assertEquals('http://localhost/es-us/page', i18n_url('page'));
        $this->assertEquals('http://localhost/es-us/products/1', i18n_url('products', 1));

        $this->i18n->setRegion('en-gb');
        $this->assertEquals('http://localhost/en-gb', i18n_url('/'));
        $this->assertEquals('http://localhost/en-gb/page', i18n_url('page'));
        $this->assertEquals('http://localhost/en-gb/products/1', i18n_url('products', 1));
    }
}
