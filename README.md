# Laravel Internationalization
[![Latest Version on Packagist](https://img.shields.io/packagist/v/webnuvola/laravel-i18n.svg?style=flat-square)](https://github.com/webnuvola/laravel-i18n)

This package allows you to split your website into multiple regions with route translation support.

**Attention:** until version 1.0.0, this package must be considered unstable. Use it carefully.

* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
  * [Route Translation](#route-translation)
* [Cache](#cache)
* [Known Issues](#known-issues)
* [Changelog](#changelog)
* [Contributing](#contributing)
  * [Security](#security)
* [Credits](#credits)
* [License](#license)

## Installation
This package supports only laravel 5.5 and you can install via composer:

``` bash
composer require webnuvola/laravel-i18n
```

The service provider and the facade `I18nRouter` will automatically get registered.

After the installation, you must publish the config file and set it up to your needs.

```bash
php artisan vendor:publish --provider="Webnuvola\Laravel\I18n\I18nServiceProvider" --tag="config"
```

## Configuration
You must configure at least one region before moving to the usage step.

When published, [the `config/i18n.php` config file](config/i18n.php) contains:

```php
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
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Default region
    |--------------------------------------------------------------------------
    |
    | The default region will be accessible without any prefix to uris.
    | Set this value to a region (e.g. en-us) or to null to disable
    | the behaviour.
    |
    */

    'default' => null,

];
```

## Usage
Open your `routes/web.php` file and replace `Route` with `I18nRoute`.

```php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

I18nRoute::get('/', function () {
    return view('welcome');
});

I18nRoute::get('test', 'TestController@test');
```

This package will register routes for the regions in the configuration file.

Helper functions like `url()` and `route()` will automatically generate the correct url
based on the request.

### Route Translation
This package allows you to translate routes using the Laravel translator service.
You just need to insert the translation key between square brackets.

For example with this `i18n.php` config file:

```php
[
    'regions' => [
        'en-us',
        'fr-fr',
        'it-it',
    ],
    
    'default' => 'en-us',
]
```

and this `web.php` file:

```php
I18nRoute::get('[routes.product]/{id}', 'ProductController@show');
```
this routes will be generated:
```text
/product/{id}
/fr-fr/produit/{id}
/it-it/prodotto/{id}
```

## Cache
We strongly recommend to cache routes by running `php artisan route:cache` in your production environment.
This package adds a layer of complexity everytime routes have to be parsed.

## Known Issues
* Helper function `action()` does not return the correct url
* Tests for this package are still missing

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing
`CONTRIBUTING` guidelines will be published soon.

### Security
If you discover any security-related issues, please email [fabio@webnuvola.com](mailto:fabio@webnuvola.com) instead of using the issue tracker.

## Credits
- [Fabio Cagliero](https://github.com/fab120)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.