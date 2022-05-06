<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network;

use Exception;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Psr\Container\ContainerInterface;

class NetworkManager
{
    protected  string $sProcess = "run";//for testing purpose !!!

    protected  int $iSequenceRowsNumber;

    public function __construct(
        protected DataStorage $dataStorage,
        protected NetworkPropagator $networkPropagator,
        protected ContainerInterface $container
    ) {
    }

    public function init(): void
    {
        $vdThresholds = $this->dataStorage->getSettingsParameter(
            'activation_function_looper',
            "thresholds"
        );
        $this->dataStorage->setVdThresholds($vdThresholds);

        $a3dWeights = $this->dataStorage->getSettingsParameter(
            'weight_generation_looper',
            "weights"
        );
        $this->dataStorage->setA3dWeights($a3dWeights);

        $this->dataStorage->setIweightsNumber();
        $this->dataStorage->setSettingsParameter(
            'weight_generation_looper',
            "weights_number",
            $this->dataStorage->getIweightsNumber()
        );

        $a2sActivationFunction = $this->dataStorage->getSettingsParameter(
            'activation_function_looper',
            "function_per_node"
        );
        $this->dataStorage->setA2sActivationFunction($a2sActivationFunction);

        $asActivationFunctionParameter = $this->dataStorage->getSettingsParameter(
            'activation_parameter_looper',
            "parameter_per_node"
        );
        $this->dataStorage->setA2dActivationFunctionParameter($asActivationFunctionParameter);

        $this->iSequenceRowsNumber = $this->dataStorage->getIsequenceRowsNumber();
    }
    /**
     * @throws Exception
     */
    public function propagateTrainData(): void
    {
        $this->sProcess = "train";

        $vdInputRow = $this->dataStorage->getVdTrainInputRow();

        $this->propagate($vdInputRow);
    }
    /**
     * @throws Exception
     */
    public function propagateTestData(): void
    {
        $this->sProcess = "test";

        $vdInputRow =  $this->dataStorage->getVdTestInputRow();

        $this->propagate($vdInputRow);
    }
    /**
     * @throws Exception
     */
    public function propagatePredictData(): void
    {
        $this->sProcess = "predict";

        $vdInputRow = $this->dataStorage->getVdPredictInputRow();

        $this->propagate($vdInputRow);
    }
    /**
     * @throws Exception
     */
    private function propagate(array $vdInputRow): void
    {
        $viNodesInLayers = $this->dataStorage->getViNodesInLayers();

        $vdThresholds = $this->dataStorage->getVdThresholds();

        $a3dWeights = $this->dataStorage->getA3dWeights();

        $a2sFunction = $this->dataStorage->getA2sActivationFunction();

        $a2dParameter = $this->dataStorage->getA2dActivationFunctionParameter();

        $this->networkPropagator->propagate(
            $this->sProcess,
            $vdInputRow,
            $viNodesInLayers,
            $vdThresholds,
            $a3dWeights,
            $a2sFunction,
            $a2dParameter
        );
        $hmNetworkState = $this->networkPropagator->getHmNetworkState();

        $this->dataStorage->setA2dFnc($hmNetworkState['a2dFnc']);

        if ($this->sProcess === 'train') {
            $this->dataStorage->setA2dDrv($hmNetworkState['a2dDrv']);
            $this->dataStorage->setA2dDrv2($hmNetworkState['a2dDrv2']);
            $this->dataStorage->setA2dSum($hmNetworkState['a2dSum']);
        }
    }
}
