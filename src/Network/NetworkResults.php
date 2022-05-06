<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Paneric\NNOptimizer\Network\Helper\NetworkResultsHelper;

class NetworkResults
{
    use ArrayTrait;

    private string $sNetType;
    private string $sTargetType;

    private int $iSequenceRowsNumber;

    private int $iSequenceResultsRowsNumber;

    private int $iTrainSequencesNumber;
    private int $iTestSequencesNumber;
    private int $iPredictSequencesNumber;

    private int $iOutputsNumber;

    private int $iResultsNumber = 1;

    private float $dTrainASE = 0.0;
    private float $dTestASE = 0.0;
    private float $dPredictASE = 0.0;

    private float $dEpochTrainMSE = 1000000.0;
    private float $dEpochTestMSE = 1000000.0;
    private float $dEpochPredictMSE = 1000000.0;

    private array $vdCurrentAllMSE;

    private array $a2dEpochTrainResults;
    private array $a2dEpochTestResults;
    private array $a2dEpochPredictResults;

    private array $joCurrentSettings;

    private array $a3dCurrentWeights;

    public function __construct(
        protected NetworkResultsHelper $networkResultsHelper,
        protected DataStorage $dataStorage
    ) {
    }

    public function init(): void
    {
        $this->iResultsNumber = (int) $this->dataStorage->getSettingsParameter(
            'process_looper',
            "results_number"
        );

        $this->sNetType = $this->dataStorage->getSettingsParameter(
            'process_looper',
            "net_type"
        );

        $this->sTargetType = $this->dataStorage->getSettingsParameter(
            'input_sequence_looper',
            "target_type"
        );


        $this->iSequenceRowsNumber = $this->dataStorage->getIsequenceRowsNumber();


        $this->iSequenceResultsRowsNumber = $this->dataStorage->getIsequenceRowsNumber();

        if ($this->sNetType === 'recurrent' && $this->sTargetType === 'singular') {
            $this->iSequenceResultsRowsNumber = 1;
        }


        $this->iTrainSequencesNumber = $this->dataStorage->getItrainSequencesNumber();
        $this->iTestSequencesNumber = $this->dataStorage->getItestSequencesNumber();
        $this->iPredictSequencesNumber = $this->dataStorage->getIpredictSequencesNumber();

        $viNodesInLayers = $this->dataStorage->getViNodesInLayers();

        $this->iOutputsNumber = $viNodesInLayers[count($viNodesInLayers) - 1];
    }

    public function initForEpochTrain(): void
    {
        $this->a2dEpochTrainResults = [];
    }

    public function initForEpochTest(): void
    {
        $this->a2dEpochTestResults = [];
    }

    public function initForEpochPredict(): void
    {
        $this->a2dEpochPredictResults = [];
    }
    public function getA2dEpochTrainResults(): array
    {
        return $this->a2dEpochTrainResults;
    }

    public function getA2dEpochTestResults(): array
    {
        return $this->a2dEpochTestResults;
    }

    public function getA2dEpochPredictResults(): array
    {
        return $this->a2dEpochPredictResults;
    }
    /**
     * @throws Exception
     */
    public function composeForEpochTrain(
        int $idxInputSet,
        int $inputSetRowsNumber,
        int $idxSetRow
    ): void {
        if (
            $this->sNetType === 'linear' &&
            $this->sTargetType === 'singular'//TODO: decide what to do with multiple
        ) {
            $idxResults = $idxInputSet * $inputSetRowsNumber + $idxSetRow;

            $vdTrainTargetRow = $this->dataStorage->getVdTrainTargetRow();
            $outputsNumber = count($vdTrainTargetRow);

            $vdFnc = $this->dataStorage->getA2dFnc();
            $vdFnc = end($vdFnc);

            $vdFnc = array_slice($vdFnc, 1, $outputsNumber);

            $this->a2dEpochTrainResults[$idxResults] = [
                $vdTrainTargetRow,
                $vdFnc
            ];
        }

        if (
            $this->sNetType === 'recurrent' &&
            $this->sTargetType === 'singular'//TODO: decide what to do with multiple
        ) {
            throw new Exception('TODO: code not prepared');
        }


//        if ($idxInputSet === 0 && $idxSetRow === 0) {
//            $this->a2dEpochTrainResults = $this->initArray(
//                'float',
//                2 * $this->iTrainSequencesNumber * $this->iSequenceResultsRowsNumber,
//                count($vdTrainTargetRow)
//            );
//            $this->dTrainASE = 0.0;
//            $this->dEpochTrainMSE = 0.0;
//        }
//
//        $epochTrainResults = $this->networkResultsHelper->composeForEpoch(
//            $idxInputSet,
//            $idxSetRow,
//            $this->iSequenceResultsRowsNumber,
//            $vdTrainTargetRow,
//            $a2dFnc,
//            $this->a2dEpochTrainResults,
//            $this->dTrainASE
//        );
//
//        $this->dTrainASE = $epochTrainResults['dASE'];
//        $this->a2dEpochTrainResults = $epochTrainResults['a2dEpochResults'];
    }

//    public function composeForEpochTrain(int $idxInputSet, int $idxSetRow): void
//    {
//        if (
//            $this->sNetType === 'recurrent' &&
//            $this->sTargetType === 'singular' &&
//            $idxSetRow < $this->iSequenceRowsNumber - 1
//        ) {
//            return;
//        }
//
//        if (
//            $this->sNetType === 'recurrent' &&
//            $this->sTargetType === 'singular' &&
//            $idxSetRow === $this->iSequenceRowsNumber - 1
//        ) {
//            $idxSetRow = 0;
//        }
//
//        $a2dFnc = $this->dataStorage->getA2dFnc();
//        $vdTrainTargetRow = $this->dataStorage->getVdTrainTargetRow();
//
//        if ($idxInputSet === 0 && $idxSetRow === 0) {
//            $this->a2dEpochTrainResults = $this->initArray(
//                'float',
//                2 * $this->iTrainSequencesNumber * $this->iSequenceResultsRowsNumber,
//                count($vdTrainTargetRow)
//            );
//            $this->dTrainASE = 0.0;
//            $this->dEpochTrainMSE = 0.0;
//        }
//
//        $epochTrainResults = $this->networkResultsHelper->composeForEpoch(
//            $idxInputSet,
//            $idxSetRow,
//            $this->iSequenceResultsRowsNumber,
//            $vdTrainTargetRow,
//            $a2dFnc,
//            $this->a2dEpochTrainResults,
//            $this->dTrainASE
//        );
//
//        $this->dTrainASE = $epochTrainResults['dASE'];
//        $this->a2dEpochTrainResults = $epochTrainResults['a2dEpochResults'];
//    }

    public function composeForEpochTest(int $idxInputSet, int $idxSetRow): void
    {
        if (
            $this->sNetType === 'recurrent' &&
            $this->sTargetType === 'singular' &&
            $idxSetRow < $this->iSequenceRowsNumber - 1
        ) {
            return;
        }

        if (
            $this->sNetType === 'recurrent' &&
            $this->sTargetType === 'singular' &&
            $idxSetRow === $this->iSequenceRowsNumber - 1
        ) {
            $idxSetRow = 0;
        }

        $a2dFnc = $this->dataStorage->getA2dFnc();
        $vdTestTargetRow = $this->dataStorage->getVdTestTargetRow();

        if ($idxInputSet === 0 && $idxSetRow === 0) {
            $this->a2dEpochTestResults = $this->initArray(
                'float',
                2 * $this->iTestSequencesNumber * $this->iSequenceResultsRowsNumber,
                count($vdTestTargetRow)
            );
            $this->dTestASE = 0.0;
            $this->dEpochTestMSE = 0.0;
        }

        $epochTestResults = $this->networkResultsHelper->composeForEpoch(
            $idxInputSet,
            $idxSetRow,
            $this->iSequenceResultsRowsNumber,
            $vdTestTargetRow,
            $a2dFnc,
            $this->a2dEpochTestResults,
            $this->dTestASE
        );

        $this->dTestASE = $epochTestResults['dASE'];
        $this->a2dEpochTestResults = $epochTestResults['a2dEpochResults'];
    }

    public function composeForEpochPredict(int $idxInputSet, int $idxSetRow): void
    {
        if (
            $this->sNetType === 'recurrent' &&
            $this->sTargetType === 'singular' &&
            $idxSetRow < $this->iSequenceRowsNumber - 1
        ) {
            return;
        }

        if (
            $this->sNetType === 'recurrent' &&
            $this->sTargetType === 'singular' &&
            $idxSetRow === $this->iSequenceRowsNumber - 1
        ) {
            $idxSetRow = 0;
        }


        $a2dFnc = $this->dataStorage->getA2dFnc();
        $vdPredictTargetRow = $this->dataStorage->getVdPredictTargetRow();

        if ($idxInputSet === 0 && $idxSetRow === 0) {
            $this->a2dEpochPredictResults = $this->initArray(
                'float',
                2 * $this->iPredictSequencesNumber * $this->iSequenceResultsRowsNumber,
                count($vdPredictTargetRow)
            );
            $this->dPredictASE = 0.0;
            $this->dEpochPredictMSE = 0.0;
        }

        $epochPredictResults = $this->networkResultsHelper->composeForEpoch(
            $idxInputSet,
            $idxSetRow,
            $this->iSequenceResultsRowsNumber,
            $vdPredictTargetRow,
            $a2dFnc,
            $this->a2dEpochPredictResults,
            $this->dPredictASE
        );

        $this->dPredictASE = $epochPredictResults['dASE'];
        $this->a2dEpochPredictResults = $epochPredictResults['a2dEpochResults'];
    }

    public function setCurrentSettings(): void
    {
        $this->joCurrentSettings = $this->dataStorage->getHmOptimizationSettings();
    }

    public function setCurrentAllMSE(): void
    {
        $this->dEpochTrainMSE = $this->dTrainASE/$this->iTrainSequencesNumber/$this->iOutputsNumber;
        $this->dEpochTestMSE = $this->dTestASE/$this->iTestSequencesNumber/$this->iOutputsNumber;
        $this->dEpochPredictMSE = $this->dPredictASE/$this->iPredictSequencesNumber/$this->iOutputsNumber;

        $this->vdCurrentAllMSE = [];

        $this->vdCurrentAllMSE[0] = $this->dEpochTrainMSE;
        $this->vdCurrentAllMSE[1] = $this->dEpochTestMSE;
        $this->vdCurrentAllMSE[2] = $this->dEpochPredictMSE;
    }

    public function setCurrentWeights(): void
    {
        $this->a3dCurrentWeights = $this->dataStorage->getA3dWeights();
    }
    /**
     * @throws Exception
     */
    public function setForTrain(): void
    {
        $trainResults = $this->networkResultsHelper->updateProcess(
            "train",
            $this->iResultsNumber,
            $this->dataStorage->getVldTrainResultsMSE(),
            $this->dataStorage->getVla2dTrainResults(),
            $this->dEpochTrainMSE,
            $this->a2dEpochTrainResults
        );

        $indexOfMSE = (Integer) $trainResults['indexOfMSE'];

        if (-1 < $indexOfMSE && $indexOfMSE < $this->iResultsNumber) {

            $this->dataStorage->setVldTrainResultsMSE(
                $trainResults['vldResultsMSE']
            );
            $this->dataStorage->setVla2dTrainResults(
                $trainResults['vla2dResultsValues']
            );

            $hmTrainResultsSettings = $this->networkResultsHelper->updateSettings(
                "train",
                $this->iResultsNumber,
                $indexOfMSE,
                $this->joCurrentSettings,
                $this->dataStorage->getVlsTrainResultsSettings(),
                $this->a3dCurrentWeights,
                $this->dataStorage->getVla3dTrainResultsWeights(),
                $this->vdCurrentAllMSE,
                $this->dataStorage->getVlvdTrainResultsAllMSE()
            );

            $this->dataStorage->setVlsTrainResultsSettings(
                $hmTrainResultsSettings['vlsSettings']
            );
            $this->dataStorage->setVla3dTrainResultsWeights(
                $hmTrainResultsSettings['vla3dWeights']
            );
            $this->dataStorage->setVlvdTrainResultsAllMSE(
                $hmTrainResultsSettings['vlvdAllMSE']
            );
        }
    }
    /**
     * @throws Exception
     */
    public function setForTest(): void
    {
        $testResults = $this->networkResultsHelper->updateProcess(
            "test",
            $this->iResultsNumber,
            $this->dataStorage->getVldTestResultsMSE(),
            $this->dataStorage->getVla2dTestResults(),
            $this->dEpochTestMSE,
            $this->a2dEpochTestResults
        );

        $indexOfMSE = (Integer) $testResults['indexOfMSE'];

        if (-1 < $indexOfMSE && $indexOfMSE < $this->iResultsNumber) {

            $this->dataStorage->setVldTestResultsMSE(
                $testResults['vldResultsMSE']
            );
            $this->dataStorage->setVla2dTestResults(
                $testResults['vla2dResultsValues']
            );

            $hmTestResultsSettings = $this->networkResultsHelper->updateSettings(
                "test",
                $this->iResultsNumber,
                $indexOfMSE,
                $this->joCurrentSettings,
                $this->dataStorage->getVlsTestResultsSettings(),
                $this->a3dCurrentWeights,
                $this->dataStorage->getVla3dTestResultsWeights(),
                $this->vdCurrentAllMSE,
                $this->dataStorage->getVlvdTestResultsAllMSE()

            );

            $this->dataStorage->setVlsTestResultsSettings(
                    $hmTestResultsSettings['vlsSettings']
            );
            $this->dataStorage->setVla3dTestResultsWeights(
                    $hmTestResultsSettings['vla3dWeights']
            );
            $this->dataStorage->setVlvdTestResultsAllMSE(
                    $hmTestResultsSettings['vlvdAllMSE']
            );
        }
    }
    /**
     * @throws Exception
     */
    public function setForPredict(): void
    {
        $predictResults = $this->networkResultsHelper->updateProcess(
            "predict",
            $this->iResultsNumber,
            $this->dataStorage->getVldPredictResultsMSE(),
            $this->dataStorage->getVla2dPredictResults(),
            $this->dEpochPredictMSE,
            $this->a2dEpochPredictResults
        );

        $indexOfMSE = (Integer) $predictResults['indexOfMSE'];

        if (-1 < $indexOfMSE && $indexOfMSE < $this->iResultsNumber) {

            $this->dataStorage->setVldPredictResultsMSE(
                $predictResults['vldResultsMSE']
            );
            $this->dataStorage->setVla2dPredictResults(
                $predictResults['vla2dResultsValues']
            );

            $hmPredictResultsSettings = $this->networkResultsHelper->updateSettings(
                "predict",
                $this->iResultsNumber,
                $indexOfMSE,
                $this->joCurrentSettings,
                $this->dataStorage->getVlsPredictResultsSettings(),
                $this->a3dCurrentWeights,
                $this->dataStorage->getVla3dPredictResultsWeights(),
                $this->vdCurrentAllMSE,
                $this->dataStorage->getVlvdPredictResultsAllMSE()
            );

            $this->dataStorage->setVlsPredictResultsSettings(
                $hmPredictResultsSettings['vlsSettings']
            );
            $this->dataStorage->setVla3dPredictResultsWeights(
                $hmPredictResultsSettings['vla3dWeights']
            );
            $this->dataStorage->setVlvdPredictResultsAllMSE(
                $hmPredictResultsSettings['vlvdAllMSE']
            );
        }
    }

    public function getdEpochTrainMSE(): float
    {
        return $this->dEpochTrainMSE;
    }

    public function getdEpochTestMSE(): float
    {
        return $this->dEpochTestMSE;
    }

    public function getdEpochPredictMSE(): float
    {
        return $this->dEpochPredictMSE;
    }
}
