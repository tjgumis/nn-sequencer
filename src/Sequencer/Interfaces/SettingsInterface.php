<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Interfaces;

interface SettingsInterface
{
    public function getSettingsIterationsNumber(array $joLooperConfig): int;

    public function setOLooperSettings(
        int $iIteration,
        array $joLooperConfig,
        array $hmOptimizationSettings
    ): array;

    public function getIIteration(): int;
}
