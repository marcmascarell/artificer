<p align="center">
  <img src="https://cloud.githubusercontent.com/assets/642299/5885691/45c6fcf8-a374-11e4-96e3-51891f2ca238.jpg" alt="Laravel Artificer"/>
</p>

[![Latest Version](https://img.shields.io/github/release/marcmascarell/laravel-artificer.svg?style=flat-square)](https://github.com/marcmascarell/laravel-artificer/releases)
[![Quality Score](https://img.shields.io/scrutinizer/g/marcmascarell/laravel-artificer.svg?style=flat-square)](https://scrutinizer-ci.com/g/marcmascarell/laravel-artificer/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Artificer is the admin panel Laravel deserves.

It's built around your Laravel's Eloquent models. It's very flexible, extensible, detects all your models and builds a beautiful interface to manage your data.

[Documentation](https://artificer.readme.io/)
--------------

Current state warning
----
It is under development. Use at your own risk.

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

Installation
--------------
Require this package in your composer.json and run composer update:

    "mascame/artificer": "dev-master"

Add the Service Provider to `app/config` at the bottom of Providers:

```php
Mascame\Artificer\ArtificerServiceProvider
```

(Optional) Manually publish migrations and config
----

By default, Artificer will auto-publish its files. However, if you need to publish manually you can do it.

```sh
php artisan vendor:publish --provider="Mascame\Artificer\ArtificerServiceProvider"
```

Contributing
----

Thank you for considering contributing! You can contribute at any time forking the project and making a pull request.

Support
----

If you need help or any kind of support, please send an e-mail to Marc Mascarell at marcmascarell@gmail.com.

License
----

MIT
