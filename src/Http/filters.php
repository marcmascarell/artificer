<?php

use Mascame\Artificer\Options\AdminOption;

//Route::filter('artificer-auth', function () {
//    return (new \Mascame\Artificer\Http\Controllers\UserController())->authFilter();
//});
//
//
//Route::filter('artificer-localization', function () {
//    $langs = AdminOption::get('localization.user_locales');
//
//    if (!in_array(LaravelLocalization::getCurrentLocale(), $langs)) {
//        LaravelLocalization::setLocale(array_keys($langs)[0]);
//    }
//
//    LaravelLocalization::setSupportedLocales($langs);
//});