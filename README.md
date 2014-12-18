Laravel Artificer (under development)
=========

Artificer is an admin package built on top of your models. It automatically detects all your models, tables and columns building a beautiful interface to manage your data. Help is needed.

Support
----

If you want to give your opinion, you can send me an [email](mailto:marcmascarell@gmail.com), comment the project directly (if you want to contribute with information or resources) or fork the project and make a pull request.

Also I will be grateful if you want to make a donation, this project hasn't got a death date and it wants to be improved constantly:

[![Website Button](http://www.rahmenversand.com/images/paypal_logo_klein.gif "Donate!")](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=marcmascarell%40gmail%2ecom&lc=US&item_name=Artificer%20Development&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest&amount=5 "Contribute to the project"){target="_blank"}

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

Add plugins you need (soon... you can find them in my repos). Please, be aware some plugins are under heavy development.

Add a theme. Default: [mascame/artificer-default-theme](https://github.com/marcmascarell/artificer-default-theme/)

Usage
--------------
Edit config files to meet your needs.

Documentation
--------------

Developing:

In workbench under mascame/ (or your fork) make the clone.
Rename laravel-artificer to artificer.
Run composer update inside of the package.
php artisan artificer:publish

Publish theme assets:

php artisan asset:publish mascame/artificer-default-theme

soon


License
----

MIT
