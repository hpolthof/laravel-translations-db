## Localization from a database

[![Latest Stable Version](https://poser.pugx.org/hpolthof/laravel-translations-db/v/stable.svg)](https://packagist.org/packages/hpolthof/laravel-translations-db)
[![License](https://poser.pugx.org/hpolthof/laravel-translations-db/license.svg)](https://packagist.org/packages/hpolthof/laravel-translations-db)
[![Total Downloads](https://poser.pugx.org/hpolthof/laravel-translations-db/d/total.png)](https://packagist.org/packages/hpolthof/laravel-translations-db)

This package was created for everyone who got tired of collecting translations in language files and maintaining dozens
of arrays, filled with lots and lots of keys.

## Installation

Require this package with composer:

```
composer require hpolthof/laravel-translation-db
```

After updating composer, we'll have to replace the TranslationServiceProvider the our ServiceProvider in config/app.php.

Find:
```
'Illuminate\Translation\TranslationServiceProvider',
```
and Replace this with:
```
'Hpolthof\Translation\ServiceProvider',
```

The ServiceProvider will now be loaded. To use this package properly you'll also need the create a table in your database,
this can be achieved by publishing the config and migration of this package.

Run the following command:
```
php artisan vendor:publish --provider='Hpolthof\Translation\ServiceProvider'
```
and afterwards run your migrations:
```
php artisan migrate
```

And that's all there is to it!

## Web interface
Although it's currently unavailable, a web interface to ease your translations once more, is scheduled to be added soon.
