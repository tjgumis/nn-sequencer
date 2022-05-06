<?php

declare(strict_types=1);

use Paneric\NNOptimizer\Sequencer\Looper;
use Paneric\NNOptimizer\Sequencer\SettingsManager;
use Psr\Container\ContainerInterface;

return [
    'activation_function_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['activation_function_looper'],
            'activation_function_looper'
        );
    },

    'activation_parameter_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['activation_parameter_looper'],
            'activation_parameter_looper'
        );
    },

    'general_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['general_looper'],
            'general_looper'
        );
    },

    'input_column_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['input_column_looper'],
            'input_column_looper'
        );
    },

    'input_scope_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['input_scope_looper'],
            'input_scope_looper'
        );
    },

    'input_sequence_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['input_sequence_looper'],
            'input_sequence_looper'
        );
    },

    'input_source_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['input_source_looper'],
            'input_source_looper'
        );
    },

    'input_transformation_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['input_transformation_looper'],
            'input_transformation_looper'
        );
    },

    'order_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['order_looper'],
            'order_looper'
        );
    },

    'process_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['process_looper'],
            'process_looper'
        );
    },

    'structure_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['structure_looper'],
            'structure_looper'
        );
    },

    'weight_elimination_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['weight_elimination_looper'],
            'weight_elimination_looper'
        );
    },

    'weight_generation_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['weight_generation_looper'],
            'weight_generation_looper'
        );
    },

    'weight_revision_looper' => static function (ContainerInterface $c): Looper {
        return new Looper(
            $c->get(SettingsManager::class),
            $c->get('looper_settings')['weight_revision_looper'],
            'weight_revision_looper'
        );
    },
];
