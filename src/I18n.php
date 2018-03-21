<?php

namespace Webnuvola\Laravel\I18n;

use InvalidArgumentException;
use Illuminate\Contracts\Foundation\Application;

class I18n
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The i18n configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The translator instance.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Current request language.
     *
     * @var string
     */
    protected $currentLanguage;

    /**
     * Current requst country.
     *
     * @var string
     */
    protected $currentCountry;

    /**
     * Create a new i18n instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $app['config']->get('i18n');
        $this->translator = $app['translator'];
    }

    /**
     * Initialize i18n.
     *
     * @return void
     */
    public function init()
    {
        if ($this->isConfigured()) {
            $this->setCurrentLanguageAndCountryFromRequest();
        }
    }

    /**
     * Returns language and country from a region string.
     *
     * @param string $region
     * @return array
     */
    protected function getLanguageAndCountry($region)
    {
        return explode('-', $region, 2);
    }

    /**
     * Set language and country from the current request.
     *
     * @return void
     */
    protected function setCurrentLanguageAndCountryFromRequest()
    {
        if (!empty($this->currentLanguage) && !empty($this->currentCountry)) {
            return;
        }

        $region = $this->app['request']->segment(1);
        $region = in_array($region, $this->getRegions(), true) ? $region : $this->getDefaultRegion();

        list($this->currentLanguage, $this->currentCountry) = $this->getLanguageAndCountry($region);

        $this->setAppLocale();
    }

    /**
     * Set language and country.
     *
     * @param  string $region
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function setCurrentLanguageAndCountry($region)
    {
        if (!in_array($region, $this->getRegions(), true)) {
            throw new InvalidArgumentException("Region \"{$region}\" is not valid; check your configuration i18n.php file");
        }

        list($this->currentLanguage, $this->currentCountry) = $this->getLanguageAndCountry($region);

        $this->setAppLocale();
    }

    /**
     * Set application locale.
     *
     * @return void
     */
    protected function setAppLocale()
    {
        $this->app->setLocale($this->getLanguage());
    }

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->currentLanguage;
    }

    /**
     * Returns current country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->currentCountry;
    }

    /**
     * Returns current region.
     *
     * @return string
     */
    public function getRegion()
    {
        return "{$this->getLanguage()}-{$this->getCountry()}";
    }

    /**
     * Set the current language and country from region.
     *
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->setCurrentLanguageAndCountry($region);
    }

    /**
     * Returns current region uri path.
     *
     * @return string
     */
    public function getRegionUriPath()
    {
        $region = $this->getRegion();

        return $region !== $this->config['default'] ? $region : '';
    }

    /**
     * Returns all available regions.
     *
     * @return array
     */
    public function getRegions()
    {
        return $this->config['regions'] ?? [];
    }

    /**
     * Returns available regions for the country.
     *
     * @param  string $country
     * @return \Illuminate\Support\Collection
     */
    public function getRegionsByCountry($country)
    {
        return collect($this->getRegions())->filter(function ($region) use ($country) {
            return str_is("*-{$country}", $region);
        });
    }

    /**
     * Returns the default region.
     *
     * @todo Specify in config file if regionUriPath must be empty in default region
     *
     * @return string
     */
    public function getDefaultRegion()
    {
        return $this->config['default'] ?? $this->getRegions()[0];
    }

    /**
     * Returns an array
     *
     * @param string $uri
     * @param string $language
     * @return array
     */
    protected function getUriReplacePairs($uri, $language)
    {
        preg_match_all('/\[([\w\-\.]+?)\]/', $uri, $matches);

        $matches = $matches[1] ?? [];
        $replacePairs = [];

        foreach ($matches as $key) {
            if ($this->translator->has($key, $language, false)) {
                $replacePairs["[{$key}]"] = $this->translator->trans($key, [], $language);
            }
        }

        return $replacePairs;
    }

    /**
     * Translates an uri.
     *
     * @param string $uri
     * @param string|null $region
     * @param array $options
     * @return string
     */
    public function translateUri($uri, $region = null, $options = [])
    {
        if ($region) {
            list($language) = $this->getLanguageAndCountry($region);
        } else {
            $region = $this->getRegion();
            $language = $this->getLanguage();
        }

        $uri = strtr($uri, $this->getUriReplacePairs($uri, $language));

        if ($options['forceRegionPrefix'] ?? false) {
            $prefix = "{$region}/";
        } else {
            $prefix = $region !== $this->config['default'] ? "{$region}/" : '';
        }

        return $prefix . ltrim($uri, '/');
    }

    /**
     * Returns true if the configuration file has at least 1 region.
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !!$this->getRegions();
    }

    /**
     * Returns available languages for a country.
     *
     * @param  string $country
     * @return array
     */
    public function getCountryLanguages($country = null)
    {
        $country = $country ?? $this->getCountry();
        $languages = [];

        foreach ($this->getRegions() as $region) {
            list($regionLanguage, $regionCountry) = $this->getLanguageAndCountry($region);

            if ($regionCountry === $country) {
                $languages[] = $regionLanguage;
            }
        }

        return $languages;
    }
}
