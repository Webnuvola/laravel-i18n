<?php

use Illuminate\Routing\Router;
use Webnuvola\Laravel\I18n\I18n;
use Webnuvola\Laravel\I18n\I18nRedirector;
use Webnuvola\Laravel\I18n\I18nRoutes;

beforeEach(function () {
    $i18nRoutes = $this->app->make(I18nRoutes::class);
    $router = $this->app->make(Router::class);

    $i18nRoutes->group(function () use ($router) {
        $router->get('/')->name('home');
        $router->get('product/{id}')->name('product');
        $router->get('signed')->name('signed');
    });

    $router->getRoutes()->refreshNameLookups();
});

test('home function', function () {
    $i18n = $this->app->make(I18n::class);
    $i18nRedirector = $this->app->make(I18nRedirector::class);

    $i18n->setRegion('it-it');
    expect($i18nRedirector->home()->getTargetUrl())->toBe('http://localhost/it-it');

    $i18n->setRegion('en-us');
    expect($i18nRedirector->home()->getTargetUrl())->toBe('http://localhost/en-us');

    $i18n->setRegion('es-us');
    expect($i18nRedirector->home()->getTargetUrl())->toBe('http://localhost/es-us');

    $i18n->setRegion('en-gb');
    expect($i18nRedirector->home()->getTargetUrl())->toBe('http://localhost/en-gb');
});

test('to function', function () {
    $i18n = $this->app->make(I18n::class);
    $i18nRedirector = $this->app->make(I18nRedirector::class);

    $i18n->setRegion('it-it');
    expect($i18nRedirector->to('page')->getTargetUrl())->toBe('http://localhost/it-it/page');

    $i18n->setRegion('en-us');
    expect($i18nRedirector->to('page')->getTargetUrl())->toBe('http://localhost/en-us/page');

    $i18n->setRegion('es-us');
    expect($i18nRedirector->to('page')->getTargetUrl())->toBe('http://localhost/es-us/page');

    $i18n->setRegion('en-gb');
    expect($i18nRedirector->to('page')->getTargetUrl())->toBe('http://localhost/en-gb/page');
});

test('route function', function () {
    $i18n = $this->app->make(I18n::class);
    $i18nRedirector = $this->app->make(I18nRedirector::class);

    $i18n->setRegion('it-it');
    expect($i18nRedirector->route('product', 5)->getTargetUrl())->toBe('http://localhost/it-it/product/5');

    $i18n->setRegion('en-us');
    expect($i18nRedirector->route('product', 5)->getTargetUrl())->toBe('http://localhost/en-us/product/5');

    $i18n->setRegion('es-us');
    expect($i18nRedirector->route('product', 5)->getTargetUrl())->toBe('http://localhost/es-us/product/5');

    $i18n->setRegion('en-gb');
    expect($i18nRedirector->route('product', 5)->getTargetUrl())->toBe('http://localhost/en-gb/product/5');
});

test('signedRoute function', function () {
    $i18n = $this->app->make(I18n::class);
    $i18nRedirector = $this->app->make(I18nRedirector::class);

    $i18n->setRegion('it-it');
    expect($i18nRedirector->signedRoute('signed')->getTargetUrl())->toStartWith('http://localhost/it-it/signed?signature=');

    $i18n->setRegion('en-us');
    expect($i18nRedirector->signedRoute('signed')->getTargetUrl())->toStartWith('http://localhost/en-us/signed?signature=');

    $i18n->setRegion('es-us');
    expect($i18nRedirector->signedRoute('signed')->getTargetUrl())->toStartWith('http://localhost/es-us/signed?signature=');

    $i18n->setRegion('en-gb');
    expect($i18nRedirector->signedRoute('signed')->getTargetUrl())->toStartWith('http://localhost/en-gb/signed?signature=');
});

test('helper functions', function () {
    $i18n = $this->app->make(I18n::class);

    $i18n->setRegion('it-it');

    expect(i18n_redirect()->home()->getTargetUrl())->toBe('http://localhost/it-it')
        ->and(i18n_redirect()->to('page')->getTargetUrl())->toBe('http://localhost/it-it/page')
        ->and(i18n_redirect('page/subpage')->getTargetUrl())->toBe('http://localhost/it-it/page/subpage')
        ->and(i18n_redirect()->route('product', 5)->getTargetUrl())->toBe('http://localhost/it-it/product/5')
        ->and(i18n_redirect()->signedRoute('signed')->getTargetUrl())->toStartWith('http://localhost/it-it/signed?signature=');
});
