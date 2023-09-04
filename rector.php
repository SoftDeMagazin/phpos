<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Php70\Rector\ClassMethod\Php4ConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/include',
        __DIR__ . '/js',
        __DIR__ . '/thirdparty',
        __DIR__ . '/views',
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(Php4ConstructorRector::class);

    // define sets of rules
       $rectorConfig->sets([
           LevelSetList::UP_TO_PHP_81
       ]);
};
