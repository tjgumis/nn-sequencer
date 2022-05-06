<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Weight;

use Paneric\NNOptimizer\DataStorage\DataStorage;
use Paneric\NNOptimizer\Network\Helper\WeightsHelper;
use Paneric\NNOptimizer\Network\NetworkManager;
use Paneric\NNOptimizer\Network\NetworkResults;
use Psr\Container\ContainerInterface;

class NetworkWeightUpdater
{
    public function __construct(
        protected DataStorage $dataStorage,
        protected NetworkManager $networkManager,
        protected NetworkResults $networkResults,
        protected WeightsHelper $weightsHelper,
        protected ContainerInterface $container,
    ) {
    }

    public function update(array $vdWeightsDelta): void
    {
        if (!empty($vdWeightsDelta)) { // if bMatrixSingularity === false

            $this->dataStorage->setA3dWeightsBefore();

            $a3dWeights = $this->weightsHelper->updateA3dWeights(
                $this->dataStorage->getViNodesInLayers(),
                $this->dataStorage->getA3dWeights(),
                $vdWeightsDelta
            );

            $this->dataStorage->setA3dWeights($a3dWeights);
        }
    }

    public function revise(): void
    {
//        $this->dataStorage->setShiftedDLambda(true);
//        $this->setDLambda(0.5);

        $this->dataStorage->setA3dWeights(
            $this->dataStorage->getA3dWeightsBefore()
        );



//        if (!empty($vdWeightsDelta)) { // if bMatrixSingularity === false
//
//            if ($dEpochTrainMSEBefore > $dEpochTrainMSEAfter) {
////                dd($dEpochTrainMSEBefore . ' ' . $dEpochTrainMSEAfter);
////                dump('OK ' . $this->dataStorage->getDLambda() . ' '. $this->dataStorage->getShiftedDLambda());
//                if ($this->dataStorage->getShiftedDLambda()) {
//                    $this->dataStorage->setShiftedDLambda(false);
////                    $this->setdLambda(2.0);
////
////                    dump('shift back ' . $this->dataStorage->getDLambda() . ' '. $this->dataStorage->getShiftedDLambda());
//                }
//
//                $this->dataStorage->setA3dWeightsBefore();
//dump('ZMIANA WAGI');
//                $a3dWeights = $this->weightsHelper->updateA3dWeights(
//                    $this->dataStorage->getViNodesInLayers(),
//                    $this->dataStorage->getA3dWeights(),
//                    $vdWeightsDelta
//                );
//
//                $this->dataStorage->setA3dWeights($a3dWeights);
//
//                return;
//            }
//
//            $this->dataStorage->setShiftedDLambda(true);
//            $this->setDLambda(0.5);
//
////            dump('back to old weights + shift ' . $this->dataStorage->getDLambda() . ' '. $this->dataStorage->getShiftedDLambda());
//
//            $this->dataStorage->setA3dWeights(
//                $this->dataStorage->getA3dWeightsBefore()
//            );
//        }
    }

    private function setDLambda(float $shift): void
    {
        $this->dataStorage->setDLambda(
            $this->dataStorage->getDLambda() * $shift
        );
    }
}
