<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Network\Activation\ActivationFunction;

class NetworkPropagator
{
    use ArrayTrait;

    private array $hmNetworkState = [];

    public function __construct(
        protected ActivationFunction $activationFunction
    ) {
    }
    /**
     * @throws Exception
     */
    public function propagate(
        string $sProcess,
        array $vdInputRow,
        array $viNodesInLayers,
        array $vdThresholds,
        array $a3dWeights,
        array $a2sFunction,
        array $a2dParameter
    ): void {
        $lr = count($viNodesInLayers); // layers
        $lc = $this->maxIntVecValue($viNodesInLayers) + 1;

        $a2dFnc  = $this->initArray('float', $lr, $lc);
        $a2dDrv  = $this->initArray('float', $lr, $lc);
        $a2dDrv2 = $this->initArray('float', $lr, $lc);
        $a2dSum  = $this->initArray('float', $lr, $lc);

        for ($l = 0; $l < $lr; $l++) {
            if($l === 0) {
                $a2dFnc[$l][0] = $vdThresholds[$l];

                for ($j = 1; $j < $viNodesInLayers[0] + 1; $j++) {
                    $a2dFnc[$l][$j] = $vdInputRow[0];
                }
            }

            if ($l > 0) {
                if ($l < $lr - 1) {
                    $a2dFnc[$l][0] = $vdThresholds[$l];
                }

                for ($j = 1; $j < $viNodesInLayers[$l] + 1; $j++) {
                    $a2dSum[$l][$j] = 0.0;

                    for ($i = 0; $i < $viNodesInLayers[$l-1] + 1; $i++) {
                        $a2dSum[$l][$j] = $a2dSum[$l][$j] + $a3dWeights[$l][$j][$i] * $a2dFnc[$l-1][$i];
                    }

                    $a2dFnc[$l][$j] = $this->activationFunction->compute(
                        "fnc" . $a2sFunction[$l][$j],
                        $a2dParameter[$l][$j],
                        $a2dSum[$l][$j]
                    );

                    if ($sProcess === 'train') {
                        $a2dDrv[$l][$j] = $this->activationFunction->compute(
                            "drv" . $a2sFunction[$l][$j],
                            $a2dParameter[$l][$j],
                            $a2dSum[$l][$j]
                        );

                        $a2dDrv2[$l][$j] = $this->activationFunction->compute(
                            "drv2" . $a2sFunction[$l][$j],
                            $a2dParameter[$l][$j],
                            $a2dSum[$l][$j]
                        );
                    }

                }
            }
        }

        $this->hmNetworkState = [];

        $this->hmNetworkState['a2dFnc'] = $a2dFnc;

        if ($sProcess === 'train') {
            $this->hmNetworkState['a2dDrv'] = $a2dDrv;
            $this->hmNetworkState['a2dDrv2'] = $a2dDrv2;
            $this->hmNetworkState['a2dSum'] = $a2dSum;
        }
    }

    public function getHmNetworkState(): array
    {
        return $this->hmNetworkState;
    }
}
