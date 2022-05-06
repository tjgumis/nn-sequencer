<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Paneric\NNOptimizer\Network\Helper\WeightsHelper;
use Paneric\NNOptimizer\Network\Interfaces\EquationResolverInterface;
use Paneric\NNOptimizer\Network\Interfaces\InputSequencerInterface;
use Paneric\NNOptimizer\Network\Interfaces\NetworkEquationInterface;
use Paneric\NNOptimizer\Network\Weight\NetworkWeightUpdater;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NetworkRunner
{
    use ArrayTrait;

    private int $iSequenceRowsNumber = 2;

    private InputSequencerInterface $inputSequencer;
    private NetworkEquationInterface $equationBuilder;
    private EquationResolverInterface $equationResolver;

    protected int $outputNodesNumber;

    protected string $sNetType;
    protected string $sTargetType;
    protected array $values;
    protected array $vdMSE;
    protected array $adMAE;
    private float $dMAE;
    private float $dMSE;
    private float $dMSEBefore;
    private float $dMSEAfter;

    public function __construct(
        protected DataStorage $dataStorage,
        protected NetworkManager $networkManager,
        protected NetworkResults $networkResults,
        protected WeightsHelper $weightsHelper,
        protected NetworkWeightUpdater $networkWeightUpdater,
        protected ContainerInterface $container,
    ) {
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function init(): void
    {
        $viNodesInLayers = $this->dataStorage->getSettingsParameter(
            'structure_looper',
            'nodes_in_layers'
        );
        $this->outputNodesNumber = end($viNodesInLayers);

        $this->inputSequencer = $this->container->get(
            $this->dataStorage->getSettingsParameter(
                'input_sequence_looper',
                "input_sequencer_marker"
            )
        );
        $this->inputSequencer->init($this->dataStorage);

        $this->equationResolver = $this->container->get(
            $this->dataStorage->getSettingsParameter(
                'weight_revision_looper',
                "equation_resolver_name"
            )
        );
        $this->equationResolver->init();

        $this->networkManager->init();

        $this->iSequenceRowsNumber = $this->dataStorage->getIsequenceRowsNumber();

        $this->equationBuilder = $this->container->get(
            $this->dataStorage->getSettingsParameter(
                'weight_revision_looper',
                "equation_name"
            )
        );
        $this->equationBuilder->init($this->dataStorage);

        $this->sNetType = $this->dataStorage->getSettingsParameter(
            'process_looper',
            "net_type"
        );

        $this->sTargetType = $this->dataStorage->getSettingsParameter(
            'input_sequence_looper',
            "target_type"
        );
    }
    public function initDMSEBefore(): float
    {
        $this->dMSEBefore = 10000.0;
        $this->dMSE = 10000.0;

        return $this->dMSEBefore;
    }

    /**
     * @throws Exception
     */
    public function train(int $iTrainSequencesNumber): void
    {
        $this->vdMSE = $this->initArray('float', $this->outputNodesNumber);
        $this->adMAE = $this->initArray('float', $this->outputNodesNumber, 1);

        $this->networkResults->initForEpochTrain();

        $this->equationBuilder->initEquationMatrices();

        for ($idxInputSet = 0; $idxInputSet < $iTrainSequencesNumber; $idxInputSet++) { // sequences iteration

            $this->inputSequencer->setAdTrainTargetSequence($idxInputSet);// AS FIRST! set of target sequence

            $this->inputSequencer->setAdTrainInputSequence($idxInputSet);// set of input sequence

            for ($idxSetRow = 0; $idxSetRow < $this->iSequenceRowsNumber; $idxSetRow++) {// rows' iteration :

                $this->inputSequencer->setVdTrainInputRow($idxSetRow);// set of input row

                $this->inputSequencer->setVdTrainTargetRow($idxSetRow); // set of target row

                $this->networkManager->propagateTrainData();

                if (
                    $this->sNetType === 'linear' &&
                    $this->sTargetType === 'singular'//TODO: decide what to do with multiple
                ) {
                    $this->equationBuilder->prepAdJacobianVdHessian();

                    $this->accumulateErrors(
                        $this->dataStorage->getVdTrainTargetRow()
                    );
                    $this->networkResults->composeForEpochTrain(
                        $idxInputSet,
                        $this->iSequenceRowsNumber,
                        $idxSetRow
                    );
                }
            }

            if (
                $this->sNetType === 'recurrent' &&
                $this->sTargetType === 'singular'//TODO: decide what to do with multiple
            ) {
                $this->accumulateErrors(
                    $this->dataStorage->getVdTrainTargetRow()
                );
            }
        }
        $this->computeErrors($iTrainSequencesNumber);

        $this->solveEquationAndUpdateWeights($iTrainSequencesNumber);
    }
    /**
     * @throws Exception
     */
    public function propagateTrainData(int $iTrainSequencesNumber): void
    {
        $this->vdMSE = $this->initArray('float', $this->outputNodesNumber);
        $this->adMAE = $this->initArray('float', $this->outputNodesNumber, 1);

        for ($idxInputSet = 0; $idxInputSet < $iTrainSequencesNumber; $idxInputSet++) { // sequences iteration

            $this->inputSequencer->setAdTrainTargetSequence($idxInputSet);// AS FIRST! set of target sequence

            $this->inputSequencer->setAdTrainInputSequence($idxInputSet);// set of input sequence

            for ($idxSetRow = 0; $idxSetRow < $this->iSequenceRowsNumber; $idxSetRow++) {// rows' iteration :

                $this->inputSequencer->setVdTrainInputRow($idxSetRow);// set of input row

                $this->inputSequencer->setVdTrainTargetRow($idxSetRow); // set of target row

                $this->networkManager->propagateTrainData();

                if (
                    $this->sNetType === 'linear' &&
                    $this->sTargetType === 'singular'//TODO: decide what to do with multiple
                ) {
                    $this->accumulateErrors(
                        $this->dataStorage->getVdTrainTargetRow()
                    );
                }
            }

            if (
                $this->sNetType === 'recurrent' &&
                $this->sTargetType === 'singular'//TODO: decide what to do with multiple
            ) {
                $this->accumulateErrors(
                    $this->dataStorage->getVdTrainTargetRow()
                );
            }
        }

        $this->computeErrors($iTrainSequencesNumber);

//        $this->networkResults->composeForEpochTest($idxInputSet, $idxSetRow);
    }

    /**
     * @throws Exception
     */
    public function propagateTestData(int $iTestSequencesNumber): void
    {
        $this->vdMSE = $this->initArray('float', $this->outputNodesNumber);
        $this->adMAE = $this->initArray('float', $this->outputNodesNumber, 1);

        $this->networkResults->initForEpochTest();

        for ($idxInputSet = 0; $idxInputSet < $iTestSequencesNumber; $idxInputSet++) { // sequences iteration

            $this->inputSequencer->setAdTestTargetSequence($idxInputSet);// AS FIRST! set of target sequence

            $this->inputSequencer->setAdTestInputSequence($idxInputSet);// set of input sequence

            for ($idxSetRow = 0; $idxSetRow < $this->iSequenceRowsNumber; $idxSetRow++){// rows' iteration :

                $this->inputSequencer->setVdTestInputRow($idxSetRow);// set of input row

                $this->inputSequencer->setVdTestTargetRow($idxSetRow); // set of target row

                $this->networkManager->propagateTestData();

                if (
                    $this->sNetType === 'linear' &&
                    $this->sTargetType === 'singular'//TODO: decide what to do with multiple
                ) {
                    $this->accumulateErrors(
                        $this->dataStorage->getVdTestTargetRow()
                    );
                }
            }

            if (
                $this->sNetType === 'recurrent' &&
                $this->sTargetType === 'singular'//TODO: decide what to do with multiple
            ) {
                $this->accumulateErrors(
                    $this->dataStorage->getVdTestTargetRow()
                );
            }
        }

        $this->computeErrors($iTestSequencesNumber);

//        $this->networkResults->composeForEpochTest($idxInputSet, $idxSetRow);
    }
    /**
     * @throws Exception
     */
    public function propagatePredictData(int $iPredictSequencesNumber): void
    {
        $this->vdMSE = $this->initArray('float', $this->outputNodesNumber);
        $this->adMAE = $this->initArray('float',$this->outputNodesNumber, 1);

        $this->networkResults->initForEpochPredict();

        for ($idxInputSet = 0; $idxInputSet < $iPredictSequencesNumber; $idxInputSet++) { // sequences iteration

            $this->inputSequencer->setAdPredictTargetSequence($idxInputSet);// AS FIRST! set of target sequence

            $this->inputSequencer->setAdPredictInputSequence($idxInputSet);// set of input sequence

            for ($idxSetRow = 0; $idxSetRow < $this->iSequenceRowsNumber; $idxSetRow++){// rows' iteration :

                $this->inputSequencer->setVdPredictInputRow($idxSetRow);// set of input row

                $this->inputSequencer->setVdPredictTargetRow($idxSetRow); // set of target row

                $this->networkManager->propagatePredictData();

                if (
                    $this->sNetType === 'linear' &&
                    $this->sTargetType === 'singular'//TODO: decide what to do with multiple
                ) {
                    $this->accumulateErrors(
                        $this->dataStorage->getVdPredictTargetRow()
                    );
                }
            }

            if (
                $this->sNetType === 'recurrent' &&
                $this->sTargetType === 'singular'//TODO: decide what to do with multiple
            ) {
                $this->accumulateErrors(
                    $this->dataStorage->getVdPredictTargetRow()
                );
            }
        }

        $this->computeErrors($iPredictSequencesNumber);

//        $this->networkResults->composeForEpochTest($idxInputSet, $idxSetRow);
    }

    public function getDMSE(): float
    {
        return $this->dMSE;
    }

    public function getDMAE(): float
    {
        return $this->dMAE;
    }
    /**
     * @throws Exception
     */
    public function solveEquationAndUpdateWeights(int $iTrainSequencesNumber): void
    {
        $this->dMSEBefore = $this->dMSE;

        $this->equationBuilder->setEquationSides($this->adMAE);

        $this->equationResolver->run();

        $this->networkWeightUpdater->update(
            $this->equationResolver->getVdWeightsDelta()
        );

        $this->propagateTrainData($iTrainSequencesNumber);

        $this->dMSEAfter = $this->dMSE;
//dump($this->dMSEBefore . ' ' . $this->dMSEAfter . ' '. ($this->dMSEAfter - $this->dMSEBefore)); sleep(5);
//        if ($this->dMSEAfter >= $this->dMSEBefore) {
//            $this->dMSEAfter = $this->dMSEBefore;
//            $this->networkWeightUpdater->revise();
//        }
    }

    private function accumulateErrors(array $vdTargetRow): void
    {
        $a2dFnc = $this->dataStorage->getA2dFnc();

        $iTargetsNumber = count($vdTargetRow);
        $iLayersNumber = count($a2dFnc);

        for ($iTarget = 0; $iTarget < $iTargetsNumber; $iTarget++) {
            $dOutput = $a2dFnc[$iLayersNumber - 1][$iTarget + 1];
            $dTarget = $vdTargetRow[$iTarget];

            $dAE = abs($dTarget - $dOutput);

            $this->adMAE[$iTarget][0] += $dAE;//medium absolute error

            $this->vdMSE[$iTarget] += $dAE * $dAE;//medium square error
        }
    }
    /**
     * @throws Exception
     */
    private function computeErrors(int $iTrainSequencesNumber): void
    {
        $this->dMAE = 0.0;
        $this->dMSE = 0.0;

        if (
            $this->sNetType === 'linear' &&
            $this->sTargetType === 'singular'//TODO: decide what to do with multiple
        ) {
            $iL = count($this->adMAE);

            for ($i = 0; $i < $iL; $i++) {
                $this->dMAE += $this->adMAE[$i][0];
                $this->dMSE += $this->vdMSE[$i];
                $this->adMAE[$i][0] = $this->adMAE[$i][0] / $iTrainSequencesNumber / $this->iSequenceRowsNumber;
                $this->vdMSE[$i] = $this->vdMSE[$i] / $iTrainSequencesNumber / $this->iSequenceRowsNumber;
            }
            $this->dMAE = $this->dMAE / $iTrainSequencesNumber / $this->iSequenceRowsNumber / count($this->adMAE);
            $this->dMSE = $this->dMSE / $iTrainSequencesNumber / $this->iSequenceRowsNumber / count($this->adMAE);

            return;
        }

        if (
            $this->sNetType === 'recurrent' &&
            $this->sTargetType === 'singular'//TODO: decide what to do with multiple
        ) {
            $iL = count($this->adMAE);

            for ($i = 0; $i < $iL; $i++) {
                $this->dMAE += $this->adMAE[0][$i];
                $this->dMSE += $this->vdMSE[$i];
                $this->adMAE[$i][0] /= $iTrainSequencesNumber;
                $this->vdMSE[$i] /= $iTrainSequencesNumber;
            }
            $this->dMAE = $this->dMAE / $iTrainSequencesNumber / count($this->adMAE);
            $this->dMSE = $this->dMSE / $iTrainSequencesNumber / count($this->adMAE);

            return;
        }

        throw new Exception();
    }

    public function getAdMAE(): array
    {
        return $this->adMAE;
    }

    public function getDMSEBefore(): float
    {
        return $this->dMSEBefore;
    }
    public function getDMSEAfter(): float
    {
        return $this->dMSEAfter;
    }
}
