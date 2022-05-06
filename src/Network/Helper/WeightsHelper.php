<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Helper;

class WeightsHelper
{
    public function updateA3dWeights(
        array $viNodesInLayers,
        array $a3dWeights,
        array $vdWeightsDelta
    ): array {
        $iw = -1;
//dump($vdWeightsDelta);
        $lL = count($viNodesInLayers);

        if (!empty($vdWeightsDelta)) {
            for ($l = 1; $l < $lL; $l++){ // each layer except the first one
                for ($j = 1; $j < $viNodesInLayers[$l] + 1; $j++){ // each neuron in layer except the bias
                    for ($i = 0; $i < $viNodesInLayers[$l-1] + 1; $i++){// each neuron in lower layer
                        $iw++;
                        $a3dWeights[$l][$j][$i] -= $vdWeightsDelta[$iw];
                    }
                }
            }
        }

        return $a3dWeights;
    }

    public function getIweightsNumber(array $viNodesInLayers): int
    {
        $iWeightsNumber = 0; // number of weights

        $lL = count($viNodesInLayers);

        for ($il = 1; $il <= $lL - 1; $il++){ // weights in hidden layers
            $iWeightsNumber = $iWeightsNumber + $viNodesInLayers[$il] * ($viNodesInLayers[$il - 1] + 1);
        }

        return $iWeightsNumber;
    }
}
