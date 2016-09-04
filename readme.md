# Artificer

[![Latest Version](https://img.shields.io/github/release/marcmascarell/laravel-artificer.svg?style=flat-square)](https://github.com/marcmascarell/laravel-artificer/releases)
[![Quality Score](https://img.shields.io/scrutinizer/g/marcmascarell/laravel-artificer.svg?style=flat-square)](https://scrutinizer-ci.com/g/marcmascarell/laravel-artificer/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

**Artificer is the admin panel Laravel deserves**.

It's built around your Laravel's Eloquent models. It's very flexible, extensible, detects all your models and builds a beautiful interface to manage your data.

##Current state warning

It is under development. Use at your own risk.

## Table of Contents

Here you will find a brief introduction, [go here for full documentation](https://artificer.readme.io/).

- <a href="#compatibility">Compatibility</a>
- <a href="#installation">Installation</a>
- <a href="https://artificer.readme.io/" target="_blank">Documentation</a>
- <a href="#support">Support</a>
- <a href="#license">License</a>

## Compatibility

 Laravel      | Artificer
:-------------|:----------
 4.x          | v0.1.4-alpha (Unsupported)
 5.3          | dev (work in progress)

##Installation

### Composer

Add Artificer to your `composer.json` file.

    "mascame/artificer": "dev-master" 

Run `composer install` to get the latest version of the package.

### Laravel

Add the Service Provider to `config/app.php` at the bottom of `providers`:

```php
\Mascame\Artificer\ArtificerServiceProvider::class
```

##Contributing

Thank you for considering contributing! You can contribute at any time forking the project and making a pull request.

You can find detailed instructions of how to setup the development environment [here](https://github.com/marcmascarell/artificer-demo).

##Support

If you need help or any kind of support, please send an e-mail to Marc Mascarell at marcmascarell@gmail.com.

##License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
