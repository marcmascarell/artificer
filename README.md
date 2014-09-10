Laravel Artificer [under heavy development]
=========

Artificer is an admin package built on top of your models. It automatically detects all your models, tables and columns building a beautiful interface to manage your data. Help is needed.

Current state advisory
----
Please don't use this package on production as it is under development.

Features
----

  - Plugins & widgets (making it really simple to extend and customize)
  - Themes
  - Beautiful interface
  - Automatic form field type detection
  - Validation
  - "Hooks" (with Laravel Events)
  - Ultra configurable


Todo
-----------

* Improve main theme
* Make plugins more friendly to use
* Relation fields
* Localization
* Login
* Tests
* Better assets
* Improve existing plugins

Installation
--------------


- Add the Service Provider to `app/config` at the bottom of Providers:

```php
Mascame\Artificer\ArtificerServiceProvider
```
- Publish assets and config

```sh
php artisan artificer:publish
```

Usage
--------------
Edit config files to meet your needs.

Documentation
--------------
soon


License
----

MIT
