<?php

namespace Webnuvola\Laravel\I18n\Test;

class RoutesTest extends TestCase
{
    /** @var \Illuminate\Routing\Router */
    protected $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->router = app('router');
    }

    public function testGetRoute()
    {
        app('i18n.routes')->group(function () {
            $this->router->get('/');
            $this->router->get('page');
        });

        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'it-it'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'en-us'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'es-us'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'en-gb'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'it-it/page'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'en-us/page'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'es-us/page'));
        $this->assertTrue($this->routerHasRouteWithUrl('GET', 'en-gb/page'));
        $this->assertCount(8, $this->router->getRoutes());
    }

    public function testGetRouteNamed()
    {
        app('i18n.routes')->group(function () {
            $this->router->get('/')->name('home');
            $this->router->name('product.')->group(function () {
                $this->router->get('product/{product}')->name('show');
            });
        });

        $routes = $this->router->getRoutes();
        $routes->refreshNameLookups();

        $this->assertTrue($this->router->has('it-it.home'));
        $this->assertTrue($this->router->has('en-us.home'));
        $this->assertTrue($this->router->has('es-us.home'));
        $this->assertTrue($this->router->has('en-gb.home'));
        $this->assertTrue($this->router->has('it-it.product.show'));
        $this->assertTrue($this->router->has('en-us.product.show'));
        $this->assertTrue($this->router->has('es-us.product.show'));
        $this->assertTrue($this->router->has('en-gb.product.show'));
        $this->assertCount(8, $routes);
    }

    public function testGetRegionMixin()
    {
        app('i18n.routes')->group(function () {
            $this->router->get('page/subpage/{id}')->name('test');
        });
        $this->router->get('otherpage/other_subpage/{id}')->name('test-noi18n');

        $routes = $this->router->getRoutes();
        $routes->refreshNameLookups();

        $this->assertEquals('it-it', $routes->getByName('it-it.test')->getRegion());
        $this->assertEquals('en-us', $routes->getByName('en-us.test')->getRegion());
        $this->assertEquals('es-us', $routes->getByName('es-us.test')->getRegion());
        $this->assertEquals('en-gb', $routes->getByName('en-gb.test')->getRegion());
        $this->assertEquals(null, $routes->getByName('test-noi18n')->getRegion());
        $this->assertCount(5, $routes);
    }

    protected function routerHasRouteWithUrl(string $method, string $url)
    {
        /** @var \Illuminate\Routing\Route $route */
        foreach ($this->router->getRoutes()->get($method) as $route) {
            if ($route->uri() === $url) {
                return true;
            }
        }

        return false;
    }
}
