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

It's flexible, extensible and builds a beautiful interface around your models.

##Current state warning

It is under development. Use at your own risk.

## Table of Contents

Here you will find a brief introduction, [go here for full documentation](https://artificer.readme.io/).

- <a href="#compatibility">Compatibility</a>
- <a href="#installation">Installation</a>
- <a href="#screenshots">Screenshots</a>
- <a href="https://artificer.readme.io/" target="_blank">Documentation</a>
- <a href="https://artificer.readme.io/docs/how-to-contribute" target="_blank">Contributing</a>
- <a href="#support">Support</a>
- <a href="#license">License</a>

## Compatibility

 Laravel      | Artificer
:-------------|:----------
 4.x          | v0.1.4-alpha (Unsupported)
 5.5          | dev (work in progress)

## Installation

### Composer

Add Artificer to your `composer.json` file.

    "mascame/artificer": "dev-master" 

Run `composer install` to get the latest version of the package.

### Laravel

Add the Service Provider to `config/app.php` at the bottom of `providers`:

```php
\Mascame\Artificer\ArtificerServiceProvider::class
```
## Screenshots
![artificer_model_index_shadow](https://cloud.githubusercontent.com/assets/642299/19166487/ffb1d638-8c07-11e6-8285-cb3f5a785a4f.png)
![artificer_extensions_shadow](https://cloud.githubusercontent.com/assets/642299/19166488/ffb3c308-8c07-11e6-867b-f42de1e851d4.png)

## Support

If you need help or any kind of support, please send an e-mail to Marc Mascarell at marcmascarell@gmail.com.

---

[![JetBrains & PhpStorm](https://ubublog.com/wp-content/uploads/logo-ps-jb.png)](https://jetbrains.com/phpstorm)  
_This project is friendly supported by [JetBrains](https://jetbrains.com) & [PhpStorm](https://jetbrains.com/phpstorm)!_

---

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
