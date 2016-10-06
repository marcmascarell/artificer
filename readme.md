<p align="center">

 <img src="https://cloud.githubusercontent.com/assets/642299/19163982/726d7934-8bfe-11e6-8805-c7a52512eb2a.png" alt="Artificer" title="Artificer" />
 <h1 align="center">Artificer</h1>
 <h3 align="center">The admin panel Laravel deserves</h3>

 <p align="center">
  <a align="center" href="https://github.com/marcmascarell/laravel-artificer/releases"><img src="https://img.shields.io/github/release/marcmascarell/laravel-artificer.svg?style=flat-square" alt="Latest Version"></a>

  <a align="center" href="https://scrutinizer-ci.com/g/marcmascarell/laravel-artificer/"><img src="https://img.shields.io/scrutinizer/g/marcmascarell/laravel-artificer.svg?style=flat-square" alt="Quality Score"></a>

  <a align="center" href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></a>
 </p>
</p>


It's built around your Laravel's Eloquent models. It's very flexible, extensible, detects all your models and builds a beautiful interface to manage your data.

##Current state warning

It is under development. Use at your own risk.

## Table of Contents

Here you will find a brief introduction, [go here for full documentation](https://artificer.readme.io/).

- <a href="#compatibility">Compatibility</a>
- <a href="#installation">Installation</a>
- <a href="https://artificer.readme.io/" target="_blank">Documentation</a>
- <a href="#support">Support</a>
- <a href="#code-of-conduct">Code of Conduct</a>
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

[How to setup the development environment](https://github.com/marcmascarell/artificer-demo).

##Support

If you need help or any kind of support, please send an e-mail to Marc Mascarell at marcmascarell@gmail.com.

##Code of Conduct

This project is committed to enforce the <a href="http://contributor-covenant.org/version/1/4/" target="_blank">"Contributor Covenant"</a> code of conduct.

##License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
