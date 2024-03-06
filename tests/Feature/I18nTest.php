<?php

use Illuminate\Http\Request;
use Webnuvola\Laravel\I18n\Exceptions\MissingConfigurationException;
use Webnuvola\Laravel\I18n\Exceptions\RegionNotValidException;
use Webnuvola\Laravel\I18n\I18n;

it('can instantiate class', function () {
    expect($this->app->make('i18n'))->toBeInstanceOf(I18n::class)
        ->and($this->app->make(I18n::class))->toBeInstanceOf(I18n::class);
});

it('can get regions', function () {
    expect($this->app->make(I18n::class)->getRegions())
        ->toBe(['it-it', 'en-us', 'es-us', 'en-gb']);
});

it('can get default region', function () {
    $i18n = $this->app->make(I18n::class);

    expect($i18n->getRegion())->toBe('it-it')
        ->and($i18n->getLanguage())->toBe('it')
        ->and($i18n->getCountry())->toBe('it')
        ->and(app()->getLocale())->toBe('it');
});

it('can set region', function () {
    $i18n = $this->app->make(I18n::class);

    $i18n->setRegion('en-us');
    expect($i18n->getRegion())->toBe('en-us')
        ->and($i18n->getLanguage())->toBe('en')
        ->and($i18n->getCountry())->toBe('us')
        ->and(app()->getLocale())->toBe('en');

    $i18n->setRegion('es-us');
    expect($i18n->getRegion())->toBe('es-us')
        ->and($i18n->getLanguage())->toBe('es')
        ->and($i18n->getCountry())->toBe('us')
        ->and(app()->getLocale())->toBe('es');

    $i18n->setRegion('en-gb');
    expect($i18n->getRegion())->toBe('en-gb')
        ->and($i18n->getLanguage())->toBe('en')
        ->and($i18n->getCountry())->toBe('gb')
        ->and(app()->getLocale())->toBe('en');
});

it('throws an exception if region doesn\'t exist', function () {
    expect(fn () => app('i18n')->setRegion('fr-fr'))
        ->toThrow(RegionNotValidException::class);
});

it('can get regions by country', function () {
    $i18n = $this->app->make(I18n::class);

    expect($i18n->getRegionsByCountry('it'))->toBe(['it-it'])
        ->and($i18n->getRegionsByCountry('us'))->toBe(['en-us', 'es-us'])
        ->and($i18n->getRegionsByCountry('gb'))->toBe(['en-gb']);
});

it('can get languages by country', function () {
    $i18n = $this->app->make(I18n::class);

    expect($i18n->getLanguagesByCountry('it'))->toBe(['it'])
        ->and($i18n->getLanguagesByCountry('us'))->toBe(['en', 'es'])
        ->and($i18n->getLanguagesByCountry('gb'))->toBe(['en']);
});

it('can set region from request', function () {
    $i18n = $this->app->make(I18n::class);

    $requestUris = [
        'it-it' => 'it-it', 'it-it/test' => 'it-it',
        'en-us' => 'en-us', 'en-us/test/sub' => 'en-us',
        'es-us' => 'es-us', 'es-us/test/sub/2' => 'es-us',
        'en-gb' => 'en-gb', 'en-gb/test/sub/2/function' => 'en-gb',
    ];

    foreach ($requestUris as $uri => $region) {
        $this->app->extend(Request::class, static function () use ($uri): Request {
            return Request::create($uri);
        });

        $i18n->setRegionFromRequest();
        expect($i18n->getRegion())->toBe($region);
    }
});

it('can get languages', function () {
    expect($this->app->make(I18n::class)->getLanguages())
        ->toBe(['it', 'en', 'es']);
});

it('can get countries', function () {
    expect($this->app->make(I18n::class)->getCountries())
        ->toBe(['it', 'us', 'gb']);
});

it('can set default region from request without region segment', function () {
    $this->app->extend(Request::class, static function (): Request {
        return Request::create('products');
    });

    $i18n = $this->app->make(I18n::class);
    $i18n->setRegionFromRequest();

    expect($i18n->getRegion())->toBe('it-it');
});

it('throws an error if it is not configure', function () {
    $this->app->make('config')->set(['i18n.regions' => []]);

    expect(fn () => new I18n($this->app))->toThrow(MissingConfigurationException::class);
});
