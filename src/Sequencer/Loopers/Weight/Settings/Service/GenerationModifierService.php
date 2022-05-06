<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Weight\Settings\Service;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;

class GenerationModifierService
{
    use ArrayTrait;

    private int $iSeedBegin;
    private int $iSeedEnd;

    public function init(array $joLooperConfig): void
    {
        $this->iSeedBegin = (int) $joLooperConfig['seed_begin'];
        $this->iSeedEnd = (int) $joLooperConfig['seed_end'];
    }

    public function getSettingsIterationsNumber(): int
    {
        return $this->iSeedEnd - $this->iSeedBegin + 1;
    }

    public function setISeed(int $iIteration): int
    {
        return $this->iSeedBegin + $iIteration;
    }
    /**
     * @throws Exception
     */
    public function setJaWeights(int $iSeed, array $viNodesInLayers): array
    {
        $weightsRows = count($viNodesInLayers); // only weighted layers
        $weightsCols = $this->maxIntVecValue($viNodesInLayers) + 1;
        $adWeights = $this->initArray('float', $weightsRows, $weightsCols, $weightsCols);

        $vdWeights = $this->randomizeWeights($iSeed, $viNodesInLayers);

        $iw = -1;
        for ($l = 1; $l < $weightsRows; $l++){
            for ($j = 1; $j < $viNodesInLayers[$l] + 1; $j++){
                for ($i = 0; $i < $viNodesInLayers[$l-1] + 1; $i++){
                    $iw++;
                    $adWeights[$l][$j][$i] = $vdWeights[$iw];
                }
            }
        }

        $jaI = [];
        for ($i = 0; $i < $weightsRows; $i++){
            $jaJ = [];
            for ($j = 0; $j < $weightsCols; $j++){
                $jaK = [];
                for ($k = 0; $k < $weightsCols; $k++){
                    if (!empty($adWeights[$i][$j][$k])) {
                        $jaK[$k] = $adWeights[$i][$j][$k];
                    }
                }

                $jaJ[$j] = $jaK;
            }

            $jaI[$i] = $jaJ;
        }

        return $jaI;
    }
    /**
     * @throws Exception
     *
     * Xavier Weight Initialization
     * https://machinelearningmastery.com/weight-initialization-for-deep-learning-neural-networks/
     */
    private function randomizeWeights(int $iSeed, array $viNodesInLayers): array
    {
        mt_srand($iSeed, MT_RAND_MT19937);

        $lL = count($viNodesInLayers);

        $iWeightsNumber = 0;

        for ($iL = $lL - 1; $iL > 0; $iL--) {
            $iWeightsNumber += $viNodesInLayers[$iL] * ($viNodesInLayers[$iL - 1] + 1);
        }

        $vdWeights = [];

        for ($iw = 0; $iw < $iWeightsNumber; $iw++) {
            $vdWeights[$iw] = random_int(1, 1000) / 1000;
        }

        $lower = -(1.0 / sqrt($viNodesInLayers[0]));
        $upper = 1.0 / sqrt($viNodesInLayers[0]);

        for ($iw = 0; $iw < $iWeightsNumber; $iw++) {
            $vdWeights[$iw] = $lower + $vdWeights[$iw] * ($upper - $lower);
            if ($vdWeights[$iw] === 0.0) {
                $vdWeights[$iw] = 1/1000;
            }
        }

        return $vdWeights;
    }
}
