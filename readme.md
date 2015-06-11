## Localization from a database

[![Latest Stable Version](https://poser.pugx.org/hpolthof/laravel-translations-db/v/stable.svg)](https://packagist.org/packages/hpolthof/laravel-translations-db)
[![License](https://poser.pugx.org/hpolthof/laravel-translations-db/license.svg)](https://packagist.org/packages/hpolthof/laravel-translations-db)
[![Total Downloads](https://poser.pugx.org/hpolthof/laravel-translations-db/d/total.png)](https://packagist.org/packages/hpolthof/laravel-translations-db)

This package was created, as an **in-place replacement** for the default TranslationServiceProvider and mend for everyone who got tired of collecting translations in language files and maintaining dozens
of arrays, filled with lots and lots of keys. Keys will be added to the database **automatically**, so no more hussling with
adding your keys to the translations file. You'll never forget to translate a key anymore! In production your keys will be **cached** to ensure the localization stays blazing fast!

![screenshot](https://cloud.githubusercontent.com/assets/1415623/8106863/4fa2c052-1045-11e5-8d7e-1655f435ee5b.png)


## Installation

Require this package with composer:

```
composer require hpolthof/laravel-translation-db
```
> Like to live on the edge?
> Use: ```composer require 'hpolthof/laravel-translations-db:*@dev'```

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

## Usage
You can just start using translations as you would normaly do with the Laravels default package. The functions ```trans()``` and ```Lang::get()``` can be used as you normaly would.
> For more information about Localization and the usage of these functions, please refer to the [Documentation](http://laravel.com/docs/5.1/localization) on the mather.

### Files are still possible
The usage of translation files is however still possible. Every translation prefixed by a namespace is parsed through the old
TranslationServiceProvider. This is needed for external packages that come with there own translation files. But in general
you shouldn't be bothered by this.

## Web interface
To prevent you from having to access the database for every translation, a web interface is available to manager your
translations.

Direct your browser to:
```
http://{projectUrl}/_translations
```
> This location can also be changed in the ```translation-db.php``` config file.

### Configure your workset
At the Translations Manager you'll have to select a certain group, as well as a locale. The first locale you'll select
represents the language you'll be using as a reference to create your translations.
In the textfield you should enter the locale you want to create or edit.
> The locale you should enter in the textfield, can be any locale. There is no need to predefine anything. The entries will be created just as you submit your translations.
After selecting these settings, just hit the Load button and all the collected translations will be listed.

### Editing
After your translation keys are loaded, a textbox will appear on each row. You can just type in your translation, when
the textbox loses it's focus, the translation will be saved.
> The saving takes place through some Ajax calls. If you are using the [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
> it will be advised to disable the Debugbar as every Ajax request slows your browser down. By default de Debugbar will
> therefor be disabled, it the Debugbar is used. If you want to leave the Debugbar on, you can just enable it within
> the ```translation-db.php``` config file.

### Turn it off
If you don't want any additional routes forced into your application, you can disable the whole web interface by
changing the ```translation-db.webinterface``` config from ```TRUE``` to ```FALSE```.
