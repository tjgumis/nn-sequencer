<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Input\Slide;

use Exception;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Paneric\NNOptimizer\Network\Interfaces\InputSequencerInterface;

class InputSlideSequencer implements InputSequencerInterface
{
    private int $iSequenceRowsNumber;

    private int $iSequenceShift;

    private int $iSequenceDelay;

    private string $sTargetType = "multiple";// for testing purpose

    private string $sNetType = "linear";// for testing purpose

    private array $viNodesInLayers;

    public function __construct(
        protected DataStorage $dataStorage,
        protected InputSlideHelper $inputSlideHelper
    ) {
    }

    public function init(DataStorage $dataStorage): void
    {
        $this->iSequenceRowsNumber = (int) $dataStorage->getSettingsParameter(
            'input_sequence_looper',
            "rows_number"
        );
        $this->dataStorage->setIsequenceRowsNumber($this->iSequenceRowsNumber);

        $this->iSequenceShift = (int) $dataStorage->getSettingsParameter(
            'input_sequence_looper',
            "shift"
        );

        $this->iSequenceDelay = (int) $dataStorage->getSettingsParameter(
            'input_sequence_looper',
            "delay"
        );

        $this->sTargetType = $dataStorage->getSettingsParameter(
                'input_sequence_looper',
                "target_type"
        );

        $this->viNodesInLayers = $dataStorage->getSettingsParameter(
            'structure_looper',
            "nodes_in_layers"
        );
        $this->dataStorage->setViNodesInLayers($this->viNodesInLayers);

        $aiNodesIndexesInLayers = $dataStorage->getSettingsParameter(
            'structure_looper',
            "nodes_indexes_in_layers"
        );
        $this->dataStorage->setAiNodesIndexesInLayers($aiNodesIndexesInLayers);

        $this->sNetType = $dataStorage->getSettingsParameter(
            'process_looper',
            "net_type"
        );

        $this->setTrainSequencesNumber();
        $this->setTestSequencesNumber();
        $this->setPredictSequencesNumber();
    }

    private function setTrainSequencesNumber(): void
    {
        $iInputLength = count($this->dataStorage->getAdTrainInput());

        $this->dataStorage->setItrainSequencesNumber($this->inputSlideHelper->setSequencesNumber(
            $iInputLength,
            $this->iSequenceDelay,
            $this->iSequenceRowsNumber,
            $this->iSequenceShift
        ));
    }

    private function setTestSequencesNumber(): void
    {
        $iInputLength = count($this->dataStorage->getAdTestInput());

        $this->dataStorage->setItestSequencesNumber($this->inputSlideHelper->setSequencesNumber(
            $iInputLength,
            $this->iSequenceDelay,
            $this->iSequenceRowsNumber,
            $this->iSequenceShift
        ));
    }

    private function setPredictSequencesNumber(): void
    {
        $iInputLength = count($this->dataStorage->getAdPredictInput());

        $this->dataStorage->setIpredictSequencesNumber($this->inputSlideHelper->setSequencesNumber(
            $iInputLength,
            $this->iSequenceDelay,
            $this->iSequenceRowsNumber,
            $this->iSequenceShift
        ));
    }

    public function setAdTrainInputSequence(int $idxInputSet): void
    {
        $iTrainSequencesNumber = $this->dataStorage->getItrainSequencesNumber();

        $adTrainInput = $this->dataStorage->getAdTrainInput();

        $adTrainInputSequence = $this->inputSlideHelper->setAdInputSequence(
            $this->iSequenceRowsNumber,
            $this->viNodesInLayers,
            $adTrainInput,
            $this->iSequenceDelay,
            $iTrainSequencesNumber,
            $this->iSequenceShift,
            $idxInputSet
        );

        $this->dataStorage->setAdTrainInputSequence($adTrainInputSequence);
    }

    public function setAdTrainTargetSequence(int $idxInputSet): void
    {
        $iTrainSequencesNumber = $this->dataStorage->getItrainSequencesNumber();

        $adTrainTarget = $this->dataStorage->getAdTrainTarget();

        $adTrainTargetSequence = $this->inputSlideHelper->setAdTargetSequence(
            $this->sTargetType,
            $this->iSequenceRowsNumber,
            $this->viNodesInLayers,
            $adTrainTarget,
            $iTrainSequencesNumber,
            $this->iSequenceShift,
            $idxInputSet
        );
        $this->dataStorage->setAdTrainTargetSequence($adTrainTargetSequence);


        if ($this->sNetType === 'recurrent' && $this->iSequenceDelay > 0) {

            $vdTrainRecurrencyInit = $this->inputSlideHelper->setVdRecurrencyInit(
                $this->viNodesInLayers,
            );
            $this->dataStorage->setVdTrainRecurrencyInit($vdTrainRecurrencyInit);
        }
    }

    public function setAdTestInputSequence(int $idxInputSet): void
    {
        $iTestSequencesNumber = $this->dataStorage->getItestSequencesNumber();

        $adTestInput = $this->dataStorage->getAdTestInput();

        $adTestInputSequence = $this->inputSlideHelper->setAdInputSequence(
            $this->iSequenceRowsNumber,
            $this->viNodesInLayers,
            $adTestInput,
            $this->iSequenceDelay,
            $iTestSequencesNumber,
            $this->iSequenceShift,
            $idxInputSet
        );

        $this->dataStorage->setAdTestInputSequence($adTestInputSequence);
    }

    public function setAdTestTargetSequence(int $idxInputSet): void
    {
        $iTestSequencesNumber = $this->dataStorage->getItestSequencesNumber();

        $adTestTarget = $this->dataStorage->getAdTestTarget();

        $adTestTargetSequence = $this->inputSlideHelper->setAdTargetSequence(
            $this->sTargetType,
            $this->iSequenceRowsNumber,
            $this->viNodesInLayers,
            $adTestTarget,
            $iTestSequencesNumber,
            $this->iSequenceShift,
            $idxInputSet
        );
        $this->dataStorage->setAdTestTargetSequence($adTestTargetSequence);


        if ($this->sNetType === 'recurrent'  && $this->iSequenceDelay > 0) {

            $vdTestRecurrencyInit = $this->inputSlideHelper->setVdRecurrencyInit(
                $this->viNodesInLayers
            );
            $this->dataStorage->setVdTestRecurrencyInit($vdTestRecurrencyInit);
        }
    }

    public function setAdPredictInputSequence(int $idxInputSet): void
    {
        $iPredictSequencesNumber = $this->dataStorage->getIpredictSequencesNumber();

        $adPredictInput = $this->dataStorage->getAdPredictInput();

        $adPredictInputSequence = $this->inputSlideHelper->setAdInputSequence(
            $this->iSequenceRowsNumber,
            $this->viNodesInLayers,
            $adPredictInput,
            $this->iSequenceDelay,
            $iPredictSequencesNumber,
            $this->iSequenceShift,
            $idxInputSet
        );

        $this->dataStorage->setAdPredictInputSequence($adPredictInputSequence);
    }

    public function setAdPredictTargetSequence(int $idxInputSet): void
    {
        $iPredictSequencesNumber = $this->dataStorage->getIpredictSequencesNumber();

        $adPredictTarget = $this->dataStorage->getAdPredictTarget();

        $adPredictTargetSequence = $this->inputSlideHelper->setAdTargetSequence(
            $this->sTargetType,
            $this->iSequenceRowsNumber,
            $this->viNodesInLayers,
            $adPredictTarget,
            $iPredictSequencesNumber,
            $this->iSequenceShift,
            $idxInputSet
        );
        $this->dataStorage->setAdPredictTargetSequence($adPredictTargetSequence);


        if ($this->sNetType === 'recurrent'  && $this->iSequenceDelay > 0) {

            $vdPredictRecurrencyInit = $this->inputSlideHelper->setVdRecurrencyInit(
                $this->viNodesInLayers
            );
            $this->dataStorage->setVdPredictRecurrencyInit($vdPredictRecurrencyInit);
        }
    }

    public function setVdTrainInputRow(int $idxSetRow): void
    {
        $adTrainInputSequence = $this->dataStorage->getAdTrainInputSequence();
        $a2dFnc = [];

        if ($idxSetRow > 0) {
            $a2dFnc = $this->dataStorage->getA2dFnc();
        }

        $vdTrainRecurrencyInit = [];

        if ($idxSetRow === 0) {
            if ($this->sNetType === 'recurrent'  && $this->iSequenceDelay > 0) {
                $vdTrainRecurrencyInit = $this->dataStorage->getVdTrainRecurrencyInit();
            }
        }


        $vdTrainInputRow = $this->inputSlideHelper->setVdInputRow(
            $vdTrainRecurrencyInit,
            $a2dFnc,
            $this->sNetType,
            $this->sTargetType,
            $this->viNodesInLayers,
            $adTrainInputSequence,
            $idxSetRow
        );
        $this->dataStorage->setVdTrainInputRow($vdTrainInputRow);
    }
    /**
     * @throws Exception
     */
    public function setVdTestInputRow(int $idxSetRow): void
    {
        $adTestInputSequence = $this->dataStorage->getAdTestInputSequence();
        $a2dFnc = $this->dataStorage->getA2dFnc();

        $vdTestRecurrencyInit = [];

        if ($idxSetRow === 0) {
            if ($this->sNetType === 'recurrent' && $this->iSequenceDelay > 0) {
                $vdTestRecurrencyInit = $this->dataStorage->getVdTestRecurrencyInit();
            }
        }


        $vdTestInputRow = $this->inputSlideHelper->setVdInputRow(
            $vdTestRecurrencyInit,
            $a2dFnc,
            $this->sNetType,
            $this->sTargetType,
            $this->viNodesInLayers,
            $adTestInputSequence,
            $idxSetRow
        );
        $this->dataStorage->setVdTestInputRow($vdTestInputRow);
    }
    /**
     * @throws Exception
     */
    public function setVdPredictInputRow(int $idxSetRow): void
    {
        $adPredictInputSequence = $this->dataStorage->getAdPredictInputSequence();
        $a2dFnc = $this->dataStorage->getA2dFnc();


        $vdPredictRecurrencyInit = [];

        if ($idxSetRow === 0) {
            if ($this->sNetType === 'recurrent' && $this->iSequenceDelay > 0) {
                $vdPredictRecurrencyInit = $this->dataStorage->getVdPredictRecurrencyInit();
            }
        }


        $vdPredictInputRow = $this->inputSlideHelper->setVdInputRow(
            $vdPredictRecurrencyInit,
            $a2dFnc,
            $this->sNetType,
            $this->sTargetType,
            $this->viNodesInLayers,
            $adPredictInputSequence,
            $idxSetRow
        );
        $this->dataStorage->setVdPredictInputRow($vdPredictInputRow);
    }

    public function setVdTrainTargetRow(int $idxSetRow): void
    {
        $adTrainTargetSequence = $this->dataStorage->getAdTrainTargetSequence();

        $this->dataStorage->setVdTrainTargetRow(
            $adTrainTargetSequence[$idxSetRow]
        );
    }

    public function setVdTestTargetRow(int $idxSetRow): void
    {
        $adTestTargetSequence = $this->dataStorage->getAdTestTargetSequence();

        $this->dataStorage->setVdTestTargetRow(
            $adTestTargetSequence[$idxSetRow]
        );
    }

    public function setVdPredictTargetRow(int $idxSetRow): void
    {
        $adPredictTargetSequence = $this->dataStorage->getAdPredictTargetSequence();

        $this->dataStorage->setVdPredictTargetRow(
            $adPredictTargetSequence[$idxSetRow]
        );
    }
}
//TODO: tests to complete
//TODO: DONE OK code restriction for vdPredictRecurrencyInit setting (only in case of recurrent and shift > 0)

