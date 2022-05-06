#!/usr/bin/env php
<?php

declare(strict_types=1);

require './vendor/autoload.php';

use DI\ContainerBuilder;
use Paneric\NNOptimizer\Command\OptimizeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\FactoryCommandLoader;

try {
    $builder = new ContainerBuilder();
    $builder->useAutowiring(true);
    $builder->useAnnotations(false);

    $appDefinitions = require './src/config/dependencies/app.php';
    $loopersDefinitions = require './src/config/dependencies/loopers.php';

    $builder->addDefinitions(array_merge($appDefinitions, $loopersDefinitions));
    $c = $builder->build();

    $commandLoader = new FactoryCommandLoader([
        'nno:start' => static function () use ($c) {return $c->get(OptimizeCommand::class);},
    ]);

    $app = new Application();
    $app->setCommandLoader($commandLoader);
    $app->run();
} catch (Exception $e) {
}
