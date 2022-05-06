<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Activation\Settings;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;

class ParameterFixedSettings extends AbstractSettings implements SettingsInterface
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

        $jaActivationParameters = $this->setJaActivationParameters($hmOptimizationSettings, $joSettings, $sComposition);

        $joSettings['parameter_per_node'] = $jaActivationParameters;

        return $joSettings;
    }
    /**
     * @throws Exception
     */
    private function setJaActivationParameters(
        array $hmOptimizationSettings,
        array $joSettings,
        String $sComposition
    ): array {
        if ($sComposition === self::ACTIVATION_FUNCTION_PER_LAYER) {
            $joStructureSettings = $hmOptimizationSettings['structure_looper'];

            return $this->setActivationParameterPerNode($joSettings, $joStructureSettings);
        }

        return $joSettings['parameter_per_node'];
    }
    /**
     * @throws Exception
     */
    private function setActivationParameterPerNode(array $joSettings, array $joStructureSettings): array
    {
        $viNodesInLayers = $joStructureSettings['nodes_in_layers'];

        $vdParameters = $joSettings['parameter_values'];

        $lLayers = count($viNodesInLayers);

        $adParameters = $this->initArray('float', $lLayers, $this->maxIntVecValue($viNodesInLayers) + 1);

        for ($iLayer = 1; $iLayer < $lLayers; $iLayer++) {
            for ($jNode = 1; $jNode < $viNodesInLayers[$iLayer] + 1; $jNode++) {
                $adParameters[$iLayer][$jNode] = $vdParameters[$iLayer];
            }
        }

        return $adParameters;
    }
}
