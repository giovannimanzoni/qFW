<?php declare(strict_types=1);

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->exclude('build')
    ->exclude('cache')
    ->exclude('tests')
    ->in('./')
;

return new Sami($iterator,array(
    'title' => 'qFW - quick Framework',
    'build_dir'            => __DIR__.'/build/api',
    'cache_dir'            => __DIR__.'/cache/api',
    'default_opened_level' => 2,
));
