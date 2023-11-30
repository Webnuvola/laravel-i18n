<?php

namespace Webnuvola\Laravel\I18n;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use Webnuvola\Laravel\I18n\Exceptions\MissingConfigurationException;
use Webnuvola\Laravel\I18n\Exceptions\RegionNotValidException;

class I18n
{
    /**
     * I18n config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Current region.
     *
     * @var string
     */
    protected string $region;

    /**
     * Current country.
     *
     * @var string
     */
    protected string $country;

    /**
     * Current language.
     *
     * @var string
     */
    protected string $language;

    /**
     * I18n constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $application
     *
     * @throws \Webnuvola\Laravel\I18n\Exceptions\MissingConfigurationException
     */
    public function __construct(
        protected Application $application,
    ) {
        $this->config = $application['config']['i18n'];

        if (! $this->getRegions()) {
            throw new MissingConfigurationException('I18n must be configured before use');
        }
    }

    /**
     * Return region.
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * Return language.
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Return country.
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Return all available regions.
     *
     * @return array
     */
    public function getRegions(): array
    {
        return $this->config['regions'] ?? [];
    }

    /**
     * Return all available regions for a country.
     *
     * @param  string $country
     * @return array
     */
    public function getRegionsByCountry(string $country): array
    {
        return collect($this->getRegions())
            ->filter(static fn (string $region): bool => Str::is("*-{$country}", $region))
            ->values()
            ->all();
    }

    /**
     * Return the default region.
     *
     * @return string
     */
    public function getDefaultRegion(): string
    {
        return $this->config['default'] ?? $this->getRegions()[0];
    }

    /**
     * Return all languages.
     *
     * @return array<int, string>
     */
    public function getLanguages(): array
    {
        return collect($this->getRegions())
            ->map(static fn (string $region): string => substr($region, 0, 2))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Return all available languages for a country.
     *
     * @param  string $country
     * @return array
     */
    public function getLanguagesByCountry(string $country): array
    {
        return array_map(static function ($region) {
            [$language,] = explode('-', $region);

            return $language;
        }, $this->getRegionsByCountry($country));
    }

    /**
     * Return all countries.
     *
     * @return array<int, string>
     */
    public function getCountries(): array
    {
        return collect($this->getRegions())
            ->map(static fn (string $region): string => substr($region, 3))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Set the current language and country from region.
     *
     * @param  string $region
     * @return void
     *
     * @throws \Webnuvola\Laravel\I18n\Exceptions\RegionNotValidException
     */
    public function setRegion(string $region): void
    {
        if (! $this->isValidRegion($region)) {
            throw new RegionNotValidException(sprintf('Region %s is not valid, update your i18n config file', $region));
        }

        [$language, $country] = explode('-', $region);

        $this->region = $region;
        $this->language = $language;
        $this->country = $country;

        $this->application->setLocale($language);
    }

    /**
     * Set current region from the request.
     *
     * @return void
     */
    public function setRegionFromRequest(): void
    {
        if (! $region = request()->segment(1)) {
            $this->setRegionFromDefault();

            return;
        }

        try {
            $this->setRegion($region);
        } catch (RegionNotValidException) {
            $this->setRegionFromDefault();
        }
    }

    /**
     * Set current region from the default one.
     *
     * @return void
     */
    public function setRegionFromDefault(): void
    {
        try {
            $this->setRegion($this->getDefaultRegion());
        } catch (RegionNotValidException) {
            //
        }
    }

    /**
     * Return true if region is valid.
     *
     * @param  string $region
     * @return bool
     */
    public function isValidRegion(string $region): bool
    {
        return in_array($region, $this->getRegions(), true);
    }
}
