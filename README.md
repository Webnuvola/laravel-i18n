# Laravel Internationalization
[![Latest Version on Packagist](https://img.shields.io/packagist/v/webnuvola/laravel-i18n.svg?style=flat-square)](https://github.com/webnuvola/laravel-i18n)

This package allows you to register i18n routes for your Laravel app.

## Installation
Install via composer:

``` bash
composer require webnuvola/laravel-i18n
```

After the installation, you must publish the config file and set it up to your needs.

```bash
php artisan vendor:publish --provider="Webnuvola\Laravel\I18n\I18nServiceProvider" --tag="config"
```

## Configuration
After publishing, the configuration will be located in `config/i18n.php`.

You must configure at least one region to use this package.

Example configuration:
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available regions
    |--------------------------------------------------------------------------
    |
    | List of languages and countries to view your site in the format {language}-{country}.
    | Available languages: https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
    | Available countries: https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
    | Examples: en-us, en-gb, it-it, ...
    |
    */

    'regions' => [
        'en-us',
        'en-gb',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default region
    |--------------------------------------------------------------------------
    |
    | The default region that will be assigned if running from console or current
    | route is not i18n. If null, the first element of regions will be used
    | as default.
    |
    */

    'default' => null,

];
```

## Usage
Define i18n routes in `routes/web.php`:

```php
use Illuminate\Routing\Router;
use Webnuvola\Laravel\I18n\Facades\I18nRoutes;

I18nRoutes::group(static function (Router $router): void {
    Route::get('/', [HomeController::class, 'show'])->name('home');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});
```
This will register the following routes (with the config file of previous step):

| Name               | Url            |
|--------------------|----------------|
| en-us.home         | /en-us         |
| en-us.profile.show | /en-us/profile |
| en-gb.home         | /en-gb         |
| en-gb.profile.show | /en-gb/profile |

### I18n functions
To set or get the current region, you can use the following methods:

```php
use Webnuvola\Laravel\I18n\Facades\I18n;

I18n::setRegion('en-us');

I18n::getRegion(); // en-us
I18n::getCountry(); // us
I18n::getLanguage(); // en
```

### Helper functions
This package will extend this default Laravel helper functions by adding i18n support:

#### url() -> i18n_url()
```php
I18n::setRegion('en-us');

url('page'); // /page
i18n_url('page'); // /en-us/page
```

#### route() -> i18n_route()
```php
I18n::setRegion('en-us');

route('profile.show'); // /profile
i18n_route('profile.show'); // /en-us/profile

// If you want a fixed region i18n url
route('en-gb.profile.show'); // /en-gb/profile
```

#### redirect() -> i18n_redirect()
```php
I18n::setRegion('en-us');

redirect('redirect-page'); // /redirect-page
i18n_redirect('redirect-page'); // /en-us/redirect-page
```

## Security
If you discover any security-related issues, please email [fabio@webnuvola.com](mailto:fabio@webnuvola.com) instead of using the issue tracker.

## Credits
- [Fabio Cagliero](https://github.com/fab120)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
