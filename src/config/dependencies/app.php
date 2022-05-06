<?php

declare(strict_types=1);

try {
    $looperSettings = json_decode(
        file_get_contents('./resources/looper_settings.json'),
        true,
        512,
        JSON_THROW_ON_ERROR
    );
} catch (JsonException $e) {
    echo sprintf(
        '%s%s%s%s%s%s%s',
        '-------------------------------' . PHP_EOL,
        '** NN-OPTIMIZER ** Fatal error:' . PHP_EOL,
        '-------------------------------' . PHP_EOL,
        'Caught Error: ' . $e->getMessage() . PHP_EOL,
        'In file: ' . $e->getFile() . '(' . $e->getLine() . ')' . PHP_EOL,
        'Stack trace:' . PHP_EOL,
        $e->getTraceAsString() . PHP_EOL
    );
}

return [
    'looper_settings' => $looperSettings,

    'fixed_loopers_markers' => [
        'order_looper',
        'general_looper',
        'input_scope_looper',
        'input_transformation_looper',
        'input_source_looper',
        'process_looper',
        'input_column_looper',
        'structure_looper',
    ],
];
