<?php

namespace Webnuvola\Laravel\I18n\Test;

use Illuminate\Http\Request;
use Webnuvola\Laravel\I18n\Exceptions\MissingConfigurationException;
use Webnuvola\Laravel\I18n\Exceptions\RegionNotValidException;
use Webnuvola\Laravel\I18n\I18n;

final class I18nTest extends TestCase
{
    /** @var \Webnuvola\Laravel\I18n\I18n */
    protected $i18n;

    public function setUp(): void
    {
        parent::setUp();

        $this->i18n = app('i18n');
    }

    public function testGetRegions()
    {
        $this->assertEquals(['it-it', 'en-us', 'es-us', 'en-gb'], $this->i18n->getRegions());
    }

    public function testDefaultRegion()
    {
        $this->assertEquals('it-it', $this->i18n->getRegion());
        $this->assertEquals('it', $this->i18n->getLanguage());
        $this->assertEquals('it', $this->i18n->getCountry());
        $this->assertEquals('it', app()->getLocale());
    }

    public function testSetRegionEnUs()
    {
        $this->i18n->setRegion('en-us');
        $this->assertEquals('en-us', $this->i18n->getRegion());
        $this->assertEquals('en', $this->i18n->getLanguage());
        $this->assertEquals('us', $this->i18n->getCountry());
        $this->assertEquals('en', app()->getLocale());
    }

    public function testSetRegionEsUs()
    {
        $this->i18n->setRegion('es-us');
        $this->assertEquals('es-us', $this->i18n->getRegion());
        $this->assertEquals('es', $this->i18n->getLanguage());
        $this->assertEquals('us', $this->i18n->getCountry());
        $this->assertEquals('es', app()->getLocale());
    }

    public function testSetRegionEnGb()
    {
        $this->i18n->setRegion('en-gb');
        $this->assertEquals('en-gb', $this->i18n->getRegion());
        $this->assertEquals('en', $this->i18n->getLanguage());
        $this->assertEquals('gb', $this->i18n->getCountry());
        $this->assertEquals('en', app()->getLocale());
    }

    public function testSetRegionFail()
    {
        $this->expectException(RegionNotValidException::class);
        $this->i18n->setRegion('fr-fr');
    }

    public function testGetRegionsByCountry()
    {
        $this->assertEquals(['it-it'], $this->i18n->getRegionsByCountry('it'));
        $this->assertEquals(['en-us', 'es-us'], $this->i18n->getRegionsByCountry('us'));
        $this->assertEquals(['en-gb'], $this->i18n->getRegionsByCountry('gb'));
    }

    public function testGetLanguagesByCountry()
    {
        $this->assertEquals(['it'], $this->i18n->getLanguagesByCountry('it'));
        $this->assertEquals(['en', 'es'], $this->i18n->getLanguagesByCountry('us'));
        $this->assertEquals(['en'], $this->i18n->getLanguagesByCountry('gb'));
    }

    public function testSetRegionFromRequest()
    {
        $requestUris = [
            'it-it' => 'it-it', 'it-it/test' => 'it-it',
            'en-us' => 'en-us', 'en-us/test/sub' => 'en-us',
            'es-us' => 'es-us', 'es-us/test/sub/2' => 'es-us',
            'en-gb' => 'en-gb', 'en-gb/test/sub/2/function' => 'en-gb',
        ];

        foreach ($requestUris as $uri => $region) {
            app()->extend(\Illuminate\Http\Request::class, static function () use ($uri) {
                return Request::create($uri);
            });

            $this->i18n->setRegionFromRequest();
            $this->assertEquals($region, $this->i18n->getRegion());
        }
    }

    public function testGetLanguages()
    {
        $this->assertEquals(['it', 'en', 'es'], $this->i18n->getLanguages());
    }

    public function testGetCountries()
    {
        $this->assertEquals(['it', 'us', 'gb'], $this->i18n->getCountries());
    }

    /**
     * @testdox Set region from request with no i18n route
     */
    public function testSetRegionFromRequestWithNoI18nRoute()
    {
        app()->extend(\Illuminate\Http\Request::class, static function () {
            return Request::create('products');
        });

        $this->i18n->setRegionFromRequest();
        $this->assertEquals('it-it', $this->i18n->getRegion());
    }

    /**
     * @testdox I18n must be configured
     */
    public function testI18nMustBeConfigured()
    {
        $this->expectException(MissingConfigurationException::class);
        config(['i18n.regions' => []]);
        new I18n(app());
    }
}
