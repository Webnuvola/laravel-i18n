<?php

use Illuminate\Routing\Router;
use Webnuvola\Laravel\I18n\I18nRoutes;

function route_exists(Router $router, string $method, string $url): bool
{
    foreach ($router->getRoutes()->get($method) as $route) {
        if ($route->uri() === $url) {
            return true;
        }
    }

    return false;
}

it('has routes', function () {
    $router = $this->app->make(Router::class);
    $i18nRoutes = $this->app->make(I18nRoutes::class);

    $i18nRoutes->group(static function () use ($router): void {
        $router->get('/');
        $router->get('page');
    });

    expect(route_exists($router, 'GET', 'it-it'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'en-us'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'es-us'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'en-gb'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'it-it/page'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'en-us/page'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'es-us/page'))->toBeTrue()
        ->and(route_exists($router, 'GET', 'en-gb/page'))->toBeTrue()
        ->and($router->getRoutes())->toHaveCount(8);
});

it('has named routes', function () {
    $router = $this->app->make(Router::class);
    $i18nRoutes = $this->app->make(I18nRoutes::class);

    $i18nRoutes->group(static function () use ($router): void {
        $router->get('/')->name('home');
        $router->name('product.')->group(function () use ($router): void {
            $router->get('product/{product}')->name('show');
        });
    });

    $router->getRoutes()->refreshNameLookups();

    expect($router->has('it-it.home'))->toBeTrue()
        ->and($router->has('en-us.home'))->toBeTrue()
        ->and($router->has('es-us.home'))->toBeTrue()
        ->and($router->has('en-gb.home'))->toBeTrue()
        ->and($router->has('it-it.product.show'))->toBeTrue()
        ->and($router->has('en-us.product.show'))->toBeTrue()
        ->and($router->has('es-us.product.show'))->toBeTrue()
        ->and($router->has('en-gb.product.show'))->toBeTrue()
        ->and($router->getRoutes())->toHaveCount(8);
});

test('route mixin', function () {
    $router = $this->app->make(Router::class);
    $i18nRoutes = $this->app->make(I18nRoutes::class);

    $i18nRoutes->group(static function () use ($router): void {
        $router->get('page/subpage/{id}')->name('test');
    });

    $router->get('otherpage/other_subpage/{id}')->name('test-noi18n');

    $routes = $router->getRoutes();
    $routes->refreshNameLookups();

    expect($routes->getByName('it-it.test')->getRegion())->toBe('it-it')
        ->and($routes->getByName('en-us.test')->getRegion())->toBe('en-us')
        ->and($routes->getByName('es-us.test')->getRegion())->toBe('es-us')
        ->and($routes->getByName('en-gb.test')->getRegion())->toBe('en-gb')
        ->and($routes->getByName('test-noi18n')->getRegion())->toBeNull()
        ->and($routes)->toHaveCount(5);
});
