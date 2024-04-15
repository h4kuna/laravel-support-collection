<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRootFiles()
    ->withPaths([
        __DIR__ . '/src',
    ])
    // This is required to include the StrictPHP Conventions
    ->withSets([\StrictPhp\Conventions\ExtensionFiles::Rector]);