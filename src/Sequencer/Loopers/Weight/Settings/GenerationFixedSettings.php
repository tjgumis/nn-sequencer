<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Weight\Settings;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Weight\Settings\Service\GenerationModifierService;

class GenerationFixedSettings extends AbstractSettings implements SettingsInterface
{
    use ArrayTrait;

    public function __construct(
        protected GenerationModifierService $generationModifierService
    ) {
    }
    /**
     * @throws Exception
     */
    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->setiIteration($iIteration);

        $joSettings = $joLooperConfig['loops'][$iIteration];
        $iSeed = (int) $joSettings['seed'];

        $viNodesInLayers = $this->setViNodesInLayers($hmOptimizationSettings);

        $jaWeightsParameters = $this->generationModifierService->setJaWeights($iSeed, $viNodesInLayers);

        $joSettings['weights'] = $jaWeightsParameters;

        return $joSettings;
    }

    private function setViNodesInLayers(array $hmOptimizationSettings): array
    {
        $joStructureSettings = $hmOptimizationSettings['structure_looper'];

        return $joStructureSettings['nodes_in_layers'];
    }
}
