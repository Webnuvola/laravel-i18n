<?php

namespace Webnuvola\Laravel\I18n;

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
    protected function setCurrentLanguageAndRegion()
    {
        if (!empty($this->currentLanguage) && !empty($this->currentCountry)) {
            return;
        }

        $segment = $this->app['request']->segment(1);
        $segment = in_array($segment, $this->getRegions(), true) ?
            $segment : $this->config['default'];

        list($this->currentLanguage, $this->currentCountry) = $this->getLanguageAndCountry($segment);
    }

    /**
     * Returns current language.
     *
     * @return string
     */
    public function getLanguage()
    {
        $this->setCurrentLanguageAndRegion();
        return $this->currentLanguage;
    }

    /**
     * Returns current country.
     *
     * @return string
     */
    public function getCountry()
    {
        $this->setCurrentLanguageAndRegion();
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
     * @return string
     */
    public function translateUri($uri, $region = null)
    {
        if ($region) {
            list($language) = $this->getLanguageAndCountry($region);
        } else {
            $region = $this->getRegion();
            $language = $this->getLanguage();
        }

        $uri = strtr($uri, $this->getUriReplacePairs($uri, $language));
        $prefix = $region !== $this->config['default'] ? "{$region}/" : '';

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
}
