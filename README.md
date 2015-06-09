<p align="center">
  <img src="https://cloud.githubusercontent.com/assets/642299/5885691/45c6fcf8-a374-11e4-96e3-51891f2ca238.jpg" alt="Laravel Artificer"/>
</p>

[![Quality Score](https://img.shields.io/scrutinizer/g/marcmascarell/laravel-artificer.svg?style=flat-square)](https://scrutinizer-ci.com/g/marcmascarell/laravel-artificer/)
[![Latest Version](https://img.shields.io/github/release/marcmascarell/laravel-artificer.svg?style=flat-square)](https://github.com/marcmascarell/laravel-artificer/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Artificer is an admin package (under development) built on top of your Eloquent models. It automatically detects all your models, tables and columns building a beautiful interface to manage your data. Help is needed.

**Package first stable version is aimed to work with Laravel 5.1 (due to the multiple config files update. The soonest we can after its released)**

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
Publish assets and config

```sh
php artisan artificer:publish
```

Changing the theme
----
Set `theme` in `app/config/packages/mascame/artificer/admin` to `your-theme-name` 

Default: [mascame/artificer-default-theme](https://github.com/marcmascarell/artificer-default-theme/)

Plugins
----

Add plugins you need. Please, be aware some plugins are under heavy development.

* [Localization](https://github.com/marcmascarell/artificer-localization-plugin)
* [Pagination](https://github.com/marcmascarell/artificer-pagination-plugin)

Usage
--------------
Edit config files to meet your needs.

Login
-------------
Make a users table like this:

```php
Schema::create('users', function(Blueprint $table)
{
	$table->increments('id');
	$table->string('email')->unique();
	$table->string('password');
	$table->string('role'); // or $table->enum('role', array('admin', 'editor', 'user', 'whatever...'));
	$table->rememberToken();
});
```

Add the methods to User:

```php
/**
 * Get the unique identifier for the user.
 *
 * @return mixed
 */
public function getAuthIdentifier()
{
    return $this->getKey();
}

/**
 * Get the password for the user.
 *
 * @return string
 */
public function getAuthPassword()
{
    return $this->password;
}
```

Documentation
--------------

Developing (Laravel 4):

* In workbench under mascame/ (or your fork namespace) make the clone.
* Rename laravel-artificer to artificer.
* Run composer update inside of the package.
* php artisan artificer:publish

Publish theme assets:

```sh
php artisan asset:publish mascame/artificer-default-theme
```

soon more

Todo
-----------

* Improve main theme
* Make plugins more friendly to use
* Relation fields
* Localization
* Tests
* Better assets
* Improve existing plugins

Plugin ideas (to be approved and done)
-----------

* Export to CSV
* Datatables
* Image gallery
* Route viewer (editor?)
* Config editor (mainly for models)
* SEO/Pages manager

Support
----

If you want to give your opinion, you can send me an [email](mailto:marcmascarell@gmail.com), comment the project directly (if you want to contribute with information or resources) or fork the project and make a pull request.

Also I will be grateful if you want to make a donation, this project hasn't got a death date and it wants to be improved constantly:

[![Website Button](http://www.rahmenversand.com/images/paypal_logo_klein.gif "Donate!")](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=marcmascarell%40gmail%2ecom&lc=US&item_name=Artificer%20Development&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest&amount=5 "Contribute to the project")


License
----

MIT
