<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Structure\Settings\Service;

class StructureModifierService
{
    private int $iLoopBegin;
    private int $iLoopEnd;

    private array $jaNodesInLayers;

    public function init(array $joLooperConfig): void
    {
        $this->iLoopBegin =  (int) $joLooperConfig['loop_begin'];
        $this->iLoopEnd =  (int) $joLooperConfig['loop_end'];

        $this->jaNodesInLayers = $joLooperConfig['nodes_in_layers'];
    }

    public function getSettingsIterationsNumber(): int
    {
        return $this->iLoopEnd - $this->iLoopBegin + 1;
    }

    public function getJaNodesInLayers(): array
    {
        return $this->jaNodesInLayers;
    }

    public function getIHiddenNodesNumber(int $iInputLayerSize, int $iIteration): int
    {
        return $iInputLayerSize + $this->iLoopBegin + $iIteration;
    }
}
