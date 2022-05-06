<?php

declare(strict_types=1);

use Paneric\NNOptimizer\DataStorage\Traits\NetworkDataStorage;
use Paneric\NNOptimizer\DataStorage\Traits\SettingsDataStorage;
use Paneric\NNOptimizer\Network\Input\InputCollector;
use Paneric\NNOptimizer\Network\NetworkProcessor;
use Paneric\NNOptimizer\Network\NetworkResultsCollector;
use Paneric\NNOptimizer\Sequencer\Sequencer;
use Psr\Container\ContainerInterface;

return [
    Sequencer::class => static function(ContainerInterface $c) {
        return new Sequencer(
            $c->get(SettingsDataStorage::class),
            $c->get(InputCollector::class),
            $c->get(NetworkDataStorage::class),
            $c->get(NetworkProcessor::class),
            $c->get(NetworkResultsCollector::class),
            $c
        );
    },
];