Laravel Artificer (under development)
=========

Artificer is an admin package built on top of your models. It automatically detects all your models, tables and columns building a beautiful interface to manage your data. Help is needed.

Current state warning
----
Please don't use this package on production as it is under development.

Features
----

  - Plugins & widgets (making it really simple to extend and customize)
  - Themes (Having a beautiful default theme)
  - Automatic form field type detection
  - Validation
  - "Hooks" (with Laravel Events)
  - Ultra configurable
  - Frontend uses Laravel's blade template engine to avoid complexity
  - Notifications
  - Simple Login system

Todo
-----------

* Improve main theme
* Make plugins more friendly to use
* Relation fields
* Localization
* Tests
* Better assets
* Improve existing plugins

Installation
--------------
Require this package in your composer.json and run composer update:

    "mascame/artificer": "dev-master"

Add the Service Provider to `app/config` at the bottom of Providers:

```php
Mascame\Artificer\ArtificerServiceProvider
```
Publish assets and config

```sh
php artisan artificer:publish
```
Require this dependency:

    "intervention/image": "2.*"
    'Intervention\Image\ImageServiceProvider'

Optional dependency for Plupload plugin:

    "jildertmiedema/laravel-plupload": "dev-master"
    'JildertMiedema\LaravelPlupload\LaravelPluploadServiceProvider'

Add a theme. Default: [mascame/artificer-default-theme](https://github.com/marcmascarell/artificer-default-theme/)

Usage
--------------
Edit config files to meet your needs.

Documentation
--------------
soon


License
----

MIT
