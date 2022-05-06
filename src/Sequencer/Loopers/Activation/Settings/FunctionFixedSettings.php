<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Activation\Settings;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;

class FunctionFixedSettings extends AbstractSettings implements SettingsInterface
{
    use ArrayTrait;

    private const ACTIVATION_FUNCTION_PER_LAYER = 'per_layer';

    private const FUNCTION_LINEAR = 'Linear';

    /**
     * @throws Exception
     */
    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->setiIteration($iIteration);

        $joSettings = $joLooperConfig['loops'][$iIteration];
        $sComposition = $joSettings['composition'];

        $jaActivationFunctions = $this->setJaActivationFunctions($hmOptimizationSettings, $joSettings, $sComposition);

        $joSettings['function_per_node'] = $jaActivationFunctions;

        return $joSettings;
    }
    /**
     * @throws Exception
     */
    private function setJaActivationFunctions(
        array $hmOptimizationSettings,
        array $joSettings,
        String $sComposition
    ): array {
        if ($sComposition === self::ACTIVATION_FUNCTION_PER_LAYER) {
            $joStructureSettings = $hmOptimizationSettings['structure_looper'];

            return $this->setActivationFunctionPerNode($joSettings, $joStructureSettings);
        }

        return $joSettings['function_per_node'];
    }
    /**
     * @throws Exception
     */
    private function setActivationFunctionPerNode(array $joSettings, array $joStructureSettings): array
    {
        $viNodesInLayers = $joStructureSettings['nodes_in_layers'];

        $vsFunctions = $joSettings['function_types'];

        $lLayers = count($viNodesInLayers);

        $asFunctions = $this->initArray('string', $lLayers, $this->maxIntVecValue($viNodesInLayers) + 1);

        for ($iLayer = 1; $iLayer < $lLayers; $iLayer++) {
            for ($jNode = 1; $jNode < $viNodesInLayers[$iLayer] + 1; $jNode++) {
                $asFunctions[$iLayer][$jNode] = $vsFunctions[$iLayer];
            }
        }

        return $asFunctions;
    }
}
