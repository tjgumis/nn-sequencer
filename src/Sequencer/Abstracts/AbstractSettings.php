<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Abstracts;

abstract class AbstractSettings
{
    protected int $iIteration;

    public function getSettingsIterationsNumber(array $joLooperConfig): int
    {
        $jaSettings = $joLooperConfig['loops'];

        return count($jaSettings);
    }

    public function setOLooperSettings(
        int $iIteration,
        array $joLooperConfig,
        array $hmOptimizationSettings
    ): array {
        $this->iIteration = $iIteration;

        return $joLooperConfig['loops'][$iIteration];
    }

    protected function  setIIteration(int $iIteration): void
    {
        $this->iIteration = $iIteration;
    }

    public function getIIteration(): int
    {
        return $this->iIteration;
    }
}
