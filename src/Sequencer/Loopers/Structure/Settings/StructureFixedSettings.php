<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Structure\Settings;

use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;

class StructureFixedSettings extends AbstractSettings implements SettingsInterface
{
    private const NET_TYPE_LINEAR = 'linear';

    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->setiIteration($iIteration);

        $joSettings = $joLooperConfig['loops'][$iIteration];

        $sNetType = $this->getSnetType($hmOptimizationSettings);

        $jaNodesInLayers = $this->prepareJaNodesInLayers($joSettings, $hmOptimizationSettings, $sNetType);

        $joSettings['nodes_in_layers'] = $jaNodesInLayers;

        $joSettings['nodes_indexes_in_layers'] = $this->setAiNodesIndexesInLayers($jaNodesInLayers);

        return $joSettings;
    }

    private function getSNetType(array $hmOptimizationSettings): string
    {
        $joProcessSettings = $hmOptimizationSettings['process_looper'];

        return $joProcessSettings['net_type'];
    }

    private function prepareJaNodesInLayers(array $joSettings, array $hmOptimizationSettings, string $sNetType): array
    {
        $jaNodesInLayers = $joSettings['nodes_in_layers'];
        $excess = $joSettings['excess'];

        $joInputColumnSettings = $hmOptimizationSettings['input_column_looper'];

        $inputColumnNumber = count($joInputColumnSettings['train_input_column_keys']);
        $targetColumnNumber = count($joInputColumnSettings['train_target_column_keys']);

        if ($sNetType !== self::NET_TYPE_LINEAR) {
            $inputColumnNumber += $targetColumnNumber;
        }

        $lLayers = count($jaNodesInLayers);

        $jaNodesInLayers[0] = $inputColumnNumber;
        $jaNodesInLayers[$lLayers - 1] = $targetColumnNumber;

        $lLayers = count($jaNodesInLayers);

        for ($iL = 1; $iL < $lLayers - 1; $iL++) {
            if ($jaNodesInLayers[$iL] === 0) {
                $jaNodesInLayers[$iL] = $inputColumnNumber;
            }
            $jaNodesInLayers[$iL] += $excess;
        }

        return $jaNodesInLayers;
    }

    private function setAiNodesIndexesInLayers(array $viNodesInLayers): array
    {
        $aiNodesIndexesInLayers = [];

        $iNode = -1;
        foreach ($viNodesInLayers as $iL => $iNodesInLayer) {
            for ($i = 0; $i < $iNodesInLayer; $i++) {
                $iNode++;
                $aiNodesIndexesInLayers[$iL][$i] = $iNode;
            }
        }

        return $aiNodesIndexesInLayers;
    }
}
