<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Interfaces;

use Paneric\NNOptimizer\DataStorage\DataStorage;

interface InputSequencerInterface
{
    public function init(DataStorage $dataStorage): void;

    public function setAdTrainInputSequence(int $idxInputSet): void;
    public function setAdTrainTargetSequence(int $idxInputSet): void;

    public function setAdTestInputSequence(int $idxInputSet): void;
    public function setAdTestTargetSequence(int $idxInputSet): void;

    public function setAdPredictInputSequence(int $idxInputSet): void;
    public function setAdPredictTargetSequence(int $idxInputSet): void;

    public function setVdTrainInputRow(int $idxSetRow): void;
    public function setVdTrainTargetRow(int $idxSetRow): void;

    public function setVdTestInputRow(int $idxSetRow): void;
    public function setVdTestTargetRow(int $idxSetRow): void;

    public function setVdPredictInputRow(int $idxSetRow): void;
    public function setVdPredictTargetRow(int $idxSetRow): void;
}
