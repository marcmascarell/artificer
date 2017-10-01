let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('./')
    .js('resources/assets/js/app.js', 'public/js')
    .copy('public/js/app.js', '../../../public/vendor/admin/js');

// .sass('resources/assets/sass/app.scss', 'public/css')
