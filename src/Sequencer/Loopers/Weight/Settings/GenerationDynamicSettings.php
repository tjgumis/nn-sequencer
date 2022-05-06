<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Weight\Settings;

use Exception;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Weight\Settings\Service\GenerationModifierService;

class GenerationDynamicSettings extends AbstractSettings implements SettingsInterface
{
    public function __construct(
        protected GenerationModifierService $generationModifierService
    ) {
    }

    public function getSettingsIterationsNumber(array $joLooperConfig): int
    {
        $this->generationModifierService->init($joLooperConfig);

        return $this->generationModifierService->getSettingsIterationsNumber();
    }
    /**
     * @throws Exception
     */
    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->setiIteration($iIteration);

        $joLooperSettings = [];

        $iSeed = $this->generationModifierService->setISeed($iIteration);

        $viNodesInLayers = $this->setViNodesInLayers($hmOptimizationSettings);

        $jaWeightsParameters = $this->generationModifierService->setJaWeights($iSeed, $viNodesInLayers);

        $joLooperSettings['weights'] = $jaWeightsParameters;

        return $joLooperSettings;
    }

    private function setViNodesInLayers(array $hmOptimizationSettings): array
    {
        $joStructureSettings = $hmOptimizationSettings['structure_looper'];

        return $joStructureSettings['nodes_in_layers'];
    }
}
