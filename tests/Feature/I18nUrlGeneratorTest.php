<?php

use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Webnuvola\Laravel\I18n\I18n;
use Webnuvola\Laravel\I18n\I18nRoutes;
use Webnuvola\Laravel\I18n\I18nUrlGenerator;

beforeEach(function () {
    $i18nRoutes = $this->app->make(I18nRoutes::class);
    $router = $this->app->make(Router::class);

    $router->get('/')->name('home-redirect');
    $router->get('select-region')->name('select-region');

    $i18nRoutes->group(static function () use ($router): void {
        $router->get('/')->name('home');
        $router->get('page')->name('page');
        $router->get('products/{id}')->name('product.show');
    });

    $this->app->make(UrlGenerator::class);
});

test('to function', function () {
    $i18n = $this->app->make(I18n::class);
    $i18nUrlGenerator = $this->app->make(I18nUrlGenerator::class);

    $i18n->setRegion('it-it');
    expect($i18nUrlGenerator->to('/'))->toBe('http://localhost/it-it')
        ->and($i18nUrlGenerator->to('page'))->toBe('http://localhost/it-it/page')
        ->and($i18nUrlGenerator->to('products', 1))->toBe('http://localhost/it-it/products/1');

    $i18n->setRegion('en-us');
    expect($i18nUrlGenerator->to('/'))->toBe('http://localhost/en-us')
        ->and($i18nUrlGenerator->to('page'))->toBe('http://localhost/en-us/page')
        ->and($i18nUrlGenerator->to('products', 1))->toBe('http://localhost/en-us/products/1');

    $i18n->setRegion('es-us');
    expect($i18nUrlGenerator->to('/'))->toBe('http://localhost/es-us')
        ->and($i18nUrlGenerator->to('page'))->toBe('http://localhost/es-us/page')
        ->and($i18nUrlGenerator->to('products', 1))->toBe('http://localhost/es-us/products/1');

    $i18n->setRegion('en-gb');
    expect($i18nUrlGenerator->to('/'))->toBe('http://localhost/en-gb')
        ->and($i18nUrlGenerator->to('page'))->toBe('http://localhost/en-gb/page')
        ->and($i18nUrlGenerator->to('products', 1))->toBe('http://localhost/en-gb/products/1');
});

test('route function', function () {
    $i18n = $this->app->make(I18n::class);
    $i18nUrlGenerator = $this->app->make(I18nUrlGenerator::class);

    $i18n->setRegion('it-it');
    expect($i18nUrlGenerator->route('home'))->toBe('http://localhost/it-it')
        ->and($i18nUrlGenerator->route('page'))->toBe('http://localhost/it-it/page')
        ->and($i18nUrlGenerator->route('product.show', 1))->toBe('http://localhost/it-it/products/1');

    $i18n->setRegion('en-us');
    expect($i18nUrlGenerator->route('home'))->toBe('http://localhost/en-us')
        ->and($i18nUrlGenerator->route('page'))->toBe('http://localhost/en-us/page')
        ->and($i18nUrlGenerator->route('product.show', 1))->toBe('http://localhost/en-us/products/1');

    $i18n->setRegion('es-us');
    expect($i18nUrlGenerator->route('home'))->toBe('http://localhost/es-us')
        ->and($i18nUrlGenerator->route('page'))->toBe('http://localhost/es-us/page')
        ->and($i18nUrlGenerator->route('product.show', 1))->toBe('http://localhost/es-us/products/1');

    $i18n->setRegion('en-gb');
    expect($i18nUrlGenerator->route('home'))->toBe('http://localhost/en-gb')
        ->and($i18nUrlGenerator->route('page'))->toBe('http://localhost/en-gb/page')
        ->and($i18nUrlGenerator->route('product.show', 1))->toBe('http://localhost/en-gb/products/1');
});

test('route function with full names and no region set', function () {
    $i18nUrlGenerator = $this->app->make(I18nUrlGenerator::class);

    expect($i18nUrlGenerator->route('home-redirect'))->toBe('http://localhost')
        ->and($i18nUrlGenerator->route('select-region'))->toBe('http://localhost/select-region')
        ->and($i18nUrlGenerator->route('it-it.home'))->toBe('http://localhost/it-it')
        ->and($i18nUrlGenerator->route('en-us.home'))->toBe('http://localhost/en-us')
        ->and($i18nUrlGenerator->route('es-us.home'))->toBe('http://localhost/es-us')
        ->and($i18nUrlGenerator->route('en-gb.home'))->toBe('http://localhost/en-gb');
});

test('helper functions', function () {
    $i18n = $this->app->make(I18n::class);

    $i18n->setRegion('it-it');

    expect(i18n_url('/'))->toBe('http://localhost/it-it')
        ->and(i18n_route('home'))->toBe('http://localhost/it-it')
        ->and(i18n_route('home-redirect'))->toBe('http://localhost');
});
