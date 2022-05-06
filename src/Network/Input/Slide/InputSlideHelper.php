<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Input\Slide;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;

class InputSlideHelper
{
    use ArrayTrait;

    public function setSequencesNumber(
        int $iInputLength,
        int $iSequenceDelay,
        int $iSequenceRowsNumber,
        int $iSequenceShift
    ): int {

        if ($iInputLength >= $iSequenceDelay + $iSequenceRowsNumber) {
            return (int) floor(($iInputLength - $iSequenceDelay - $iSequenceRowsNumber) / $iSequenceShift) + 1;
        }

        return 0;
    }

    public function setAdInputSequence(
        int $iSequenceRowsNumber,
        array $viNodesInLayers,
        array $adInput,
        int $iSequenceDelay,
        int $iSequencesNumber,
        int $iSequenceShift,
        int $idxInputSet
    ): array {
        $adInputSequence = $this->initArray('float', $iSequenceRowsNumber, $viNodesInLayers[0]);

        $idxInputRow0 =
            count($adInput) - $iSequenceDelay - ($iSequencesNumber - 1) * $iSequenceShift - $iSequenceRowsNumber;

        $idxInputRow = $idxInputRow0 + $idxInputSet * $iSequenceShift;

        $icL = count($adInput[0]);
        for ($ir = 0; $ir < $iSequenceRowsNumber; $ir++) {
            for ($ic = 0; $ic < $icL; $ic++) {
                $adInputSequence[$ir][$ic] = $adInput[$idxInputRow + $ir][$ic]; // rows order not reversed !!!
            }
        }

        return $adInputSequence;
    }

    public function setAdTargetSequence(
        string $sTargetType,
        int $iSequenceRowsNumber,
        array $viNodesInLayers,
        array $adTarget,
        int $iSequencesNumber,
        int $iSequenceShift,
        int $idxInputSet
    ): array {
        $adTargetSequence = $this->initArray(
            'float',
            $iSequenceRowsNumber,
            $viNodesInLayers[count($viNodesInLayers) - 1]
        );

        $idxTargetRow0 = count($adTarget) - ($iSequencesNumber - 1) * $iSequenceShift - $iSequenceRowsNumber;

        $idxTargetRow = $idxTargetRow0 + $idxInputSet * $iSequenceShift;

        $icL = $viNodesInLayers[count($viNodesInLayers) - 1];

        if ($sTargetType === 'multiple') {
            for ($ir = 0; $ir < $iSequenceRowsNumber; $ir++) {
                for ($ic = 0; $ic < $icL; $ic++) {
                    $adTargetSequence[$ir][$ic] = $adTarget[$idxTargetRow + $ir][$ic];
                }
            }
        }


        if ($sTargetType === 'singular'){

            $idxTargetRow = $idxTargetRow0 + $idxInputSet * $iSequenceShift + $iSequenceRowsNumber - 1;

            $icL = $viNodesInLayers[count($viNodesInLayers) - 1];

            for ($ir = 0; $ir < $iSequenceRowsNumber; $ir++) {
                for ($ic = 0; $ic < $icL; $ic++) {
                    $adTargetSequence[$ir][$ic] = $adTarget[$idxTargetRow][$ic];
                }
            }
        }

        return $adTargetSequence;
    }

    public function setVdRecurrencyInit(
        array $viNodesInLayers
    ): array {
        return $this->initArray(
            'float',
            $viNodesInLayers[count($viNodesInLayers) - 1]
        );
    }

    /**
     * @throws Exception
     */
    public function setVdInputRow(
        array $vdRecurrencyInit,
        array $a2dFnc,
        string $sNetType,
        string $sTargetType,
        array $viNodesInLayers,
        array $adInputSequence,
        int $idxSetRow
    ): array {
        $vdInput = [];

        if ($sTargetType === 'singular') {
            if ($sNetType === 'linear') {

                $lr = $viNodesInLayers[0];
                $vdInput = $this->initArray(
                    'float',
                    $lr
                );

                for ($i = 0; $i < $lr; $i++) {
                    $vdInput[$i] = $adInputSequence[$idxSetRow][$i];
                }
            }

            if ($sNetType === 'recurrent') {

                $lr = $viNodesInLayers[0];
                $vdInput = $this->initArray(
                    'float',
                    $lr
                );

                $iL = $viNodesInLayers[0] - $viNodesInLayers[count($viNodesInLayers) - 1];

                for ($i = 0; $i < $iL; $i++) {// base data input

                    $vdInput[$i] = $adInputSequence[$idxSetRow][$i];
                }

                if ($idxSetRow === 0) {
                    $iR = -1;

                    $i0 = $viNodesInLayers[0] - $viNodesInLayers[count($viNodesInLayers) - 1];

                    for ($i = $i0; $i < $lr; $i++) {

                        $iR++;

                        $vdInput[$i] = $vdRecurrencyInit[$iR];
                    }
                } else {
                    $ii = 0;

                    $i0 = $viNodesInLayers[0] - $viNodesInLayers[count($viNodesInLayers) - 1];

                    for ($i = $i0; $i < $lr; $i++) {
                        $ii++;

                        $vdInput[$i] = $a2dFnc[count($viNodesInLayers) - 1][$ii];
                    }
                }
            }
        }

        if ($sTargetType === 'multiple') {
            throw new Exception('TODO: code not completed. Option "multiple not yet considered."');
        }

        return $vdInput;
    }
}
