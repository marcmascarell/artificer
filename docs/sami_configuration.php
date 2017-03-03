<?php

//  php vendor/sami/sami/sami.php update docs/sami_configuration.php

require __DIR__.'/../vendor/autoload.php';

use \Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in('src');

return new Sami($iterator, [
    'title'                => 'Artificer API',
    'theme'                => 'enhanced',
    'build_dir'            => __DIR__.'/API/API',
    'cache_dir'            => __DIR__.'/_build/cache',
    'default_opened_level' => 2,
]);
