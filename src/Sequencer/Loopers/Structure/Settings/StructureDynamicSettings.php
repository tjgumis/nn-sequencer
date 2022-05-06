<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Structure\Settings;

use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Structure\Settings\Service\StructureModifierService;

class StructureDynamicSettings extends AbstractSettings implements SettingsInterface
{
    private const NET_TYPE_LINEAR = 'linear';

    public function __construct(
        protected StructureModifierService $structureModifierService
    ) {
    }

    public function getSettingsIterationsNumber(array $joLooperConfig): int
    {
        $this->structureModifierService->init($joLooperConfig);

        return $this->structureModifierService->getSettingsIterationsNumber();
    }

    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->setiIteration($iIteration);

        $joInputColumnSettings = $hmOptimizationSettings['input_column_looper'];
        $iInputNodesNumber = count($joInputColumnSettings['train_input_column_keys']);

        $joProcessSettings = $hmOptimizationSettings['process_looper'];
        $sNetType = $joProcessSettings['net_type'];

        $jaNodesInLayers = $this->structureModifierService->getJaNodesInLayers();

        $iOutputNodesNumber = $jaNodesInLayers[count($jaNodesInLayers) - 1];

        $iInputLayerSize = $this->setIInputLayerSize($iOutputNodesNumber, $iInputNodesNumber, $sNetType);

        $iHiddenNodesNumber = $this->structureModifierService->getiHiddenNodesNumber($iInputLayerSize, $iIteration);

        $jaNodesInLayers[0] = $iInputLayerSize;
        $jaNodesInLayers[1] = $iHiddenNodesNumber;

        $joLooperSettings['nodes_in_layers'] = $jaNodesInLayers;

        return $joLooperSettings;
    }

    private function setIInputLayerSize(int $iOutputNodesNumber, int $iInputNodesNumber, String $sNetType): int
    {
        if ($sNetType === self::NET_TYPE_LINEAR) {
            return $iInputNodesNumber;
        }

        return $iInputNodesNumber + $iOutputNodesNumber;
    }
}
