# Changelog
All notable changes to `laravel-i18n` will be documented in this file.

## 0.1.8 - 2018-01-30
* Get available languages for a country with function `getCountryLanguages($country)`

## 0.1.7 - 2018-01-30
* `forceRegionPrefix()` works also on resources

## 0.1.6 - 2018-01-29
* Improved route registration logic
* On routes you can call `forceRegionPrefix()` to force region when the `default` setting is set

## 0.1.5 - 2018-01-26
* I18n: manually set the current region with `setRegion($region)`
* I18n: added `getDefaultRegion()` method

## 0.1.4 - 2018-01-25
* Helper `route()` now returns also routes non internationalized
* The application locale is now set during `I18nServiceProvider` boot

## 0.1.3 - 2018-01-25
* Return `$this` on magic method `__call` for `RouteCollection` and `PendingResourceRegistrationCollection`

## 0.1.2 - 2018-01-25
* Add `ifregion`, `ifnotregion`, `iflanguage`, `ifnotlanguage`, `ifcountry`, `ifnotcountry` blade extensions 

## 0.1.1 - 2018-01-24
* Add `I18n` facade
* Update `composer.json` keywords

## 0.1.0 - 2018-01-24
First release still under development