<p align="center">
  <img src="https://cloud.githubusercontent.com/assets/642299/5885691/45c6fcf8-a374-11e4-96e3-51891f2ca238.jpg" alt="Laravel Artificer"/>
</p>

[![Quality Score](https://img.shields.io/scrutinizer/g/marcmascarell/laravel-artificer.svg?style=flat-square)](https://scrutinizer-ci.com/g/marcmascarell/laravel-artificer/)
[![Latest Version](https://img.shields.io/github/release/marcmascarell/laravel-artificer.svg?style=flat-square)](https://github.com/marcmascarell/laravel-artificer/releases)
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
Publish migrations and config

```sh
php artisan vendor:publish --provider="Mascame\Artificer\ArtificerServiceProvider"
```

For only **config** add:
```sh
--tag="config"
```

For only **migrations** add:
```sh
--tag="migrations"
```

Support
----

If you want to give your opinion, you can send me an [email](mailto:marcmascarell@gmail.com), comment the project directly (if you want to contribute with information or resources) or fork the project and make a pull request.

Also I will be grateful if you want to make a donation, this project hasn't got a death date and it wants to be improved constantly:

[![Website Button](https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-200px.png "Donate!")](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=marcmascarell%40gmail%2ecom&lc=US&item_name=Artificer%20Development&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest&amount=5 "Contribute to the project")


License
----

MIT
