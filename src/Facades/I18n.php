<?php

namespace Webnuvola\Laravel\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getRegion()
 * @method static string getLanguage()
 * @method static string getCountry()
 * @method static array getRegions()
 * @method static array getRegionsByCountry(string $country)
 * @method static array getLanguagesByCountry(string $country)
 * @method static string getDefaultRegion()
 * @method static void setRegion(string $region)
 * @method static void setRegionFromRequest()
 * @method static void setRegionFromDefault()
 *
 * @see \Webnuvola\Laravel\I18n\I18n
 */
class I18n extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'i18n';
    }
}
