<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Service;

use Exception;

class ScopeRangesModifierService
{
    private int $iScopeBegin;
    private int $iScopeEnd;

    private int $iTrainInitInputBegin;
    private int $iTrainInitInputEnd;
    private int $iTestInitInputBegin;
    private int $iTestInitInputEnd;
    private int $iPredictInitInputBegin;
    private int $iPredictInitInputEnd;

    private int $iInputRangeShiftBegin;
    private int $iInputRangeShiftEnd;

    private int $iInputShift;
    private int $iInputShiftBegin;
    private int $iInputShiftEnd;

    private string $sTrainInputShiftActive;
    private string $sTestInputShiftActive;
    private string $sPredictInputShiftActive;

    /**
     * @throws Exception
     */
    public function init(array $joLooperConfig): void
    {
        $this->iScopeBegin = $joLooperConfig['scope_begin'];
        $this->iScopeEnd = $joLooperConfig['scope_end'];

        $this->iTrainInitInputBegin = $joLooperConfig['train_init_input_begin'];
        $this->iTrainInitInputEnd = $joLooperConfig['train_init_input_end'];
        $this->iTestInitInputBegin = $joLooperConfig['test_init_input_begin'];
        $this->iTestInitInputEnd = $joLooperConfig['test_init_input_end'];
        $this->iPredictInitInputBegin = $joLooperConfig['predict_init_input_begin'];
        $this->iPredictInitInputEnd = $joLooperConfig['predict_init_input_end'];

        $this->sTrainInputShiftActive = $joLooperConfig['train_input_shift_active'];
        $this->sTestInputShiftActive = $joLooperConfig['test_input_shift_active'];
        $this->sPredictInputShiftActive = $joLooperConfig['predict_input_shift_active'];

        $bDistribution = $this->isValidDistribution(
            $this->iTrainInitInputBegin,
            $this->iTrainInitInputEnd,
            $this->iTestInitInputBegin,
            $this->iTestInitInputEnd,
            $this->iPredictInitInputBegin,
            $this->iPredictInitInputEnd
        );

        if (!$bDistribution) {
            throw new Exception("Illegal input ranges.");
        }

        $this->iInputRangeShiftBegin = $joLooperConfig['input_range_shift_begin'];
        $this->iInputRangeShiftEnd = $joLooperConfig['input_range_shift_end'];

        $this->iInputShift = $joLooperConfig['input_shift'];
        $this->iInputShiftBegin = $joLooperConfig['input_shift_begin'];
        $this->iInputShiftEnd = $joLooperConfig['input_shift_end'];

        if (
            $this->iInputRangeShiftEnd < $this->iInputRangeShiftBegin ||
            $this->iInputShiftEnd < $this->iInputShiftBegin
        ) {
            throw new Exception("Illegal input shift.");
        }
    }

    public function getSettingsIterationsNumber(): int
    {
        $iSettingsIterationNumber = 0;

        for (
            $iRange = $this->iInputRangeShiftBegin;
            $iRange < $this->iInputRangeShiftEnd + 1;
            $iRange++
        ) {
            $iRangeChange = $iRange;

            for (
                $iInput = $this->iInputShiftBegin;
                $iInput < $this->iInputShiftEnd + 1;
                $iInput++
            ) {
                $iInputChange = $iInput * $this->iInputShift;

                $viInputLimiters = $this->setInputLimiters($iRangeChange, $iInputChange);

                $bDistribution = $this->isValidDistribution(
                    $viInputLimiters[0],
                    $viInputLimiters[1],
                    $viInputLimiters[2],
                    $viInputLimiters[3],
                    $viInputLimiters[4],
                    $viInputLimiters[5]
                );

                if ($bDistribution) {
                    $iSettingsIterationNumber++;
                }
            }
        }

        return $iSettingsIterationNumber;
    }
    /**
     * @throws Exception
     */
    public function setIterationRanges(int $iIteration, array $jLooperSettings): array
    {
        $iSettingsIteration = -1;

        for (
            $iRange = $this->iInputRangeShiftBegin;
            $iRange < $this->iInputRangeShiftEnd + 1;
            $iRange++
        ) {
            $iRangeChange = $iRange;

            for (
                $iInput = $this->iInputShiftBegin;
                $iInput < $this->iInputShiftEnd + 1;
                $iInput++
            ) {
                $iInputChange = $iInput * $this->iInputShift;

                $viInputLimiters = $this->setInputLimiters($iRangeChange, $iInputChange);

                $bDistribution = $this->isValidDistribution(
                    $viInputLimiters[0],
                    $viInputLimiters[1],
                    $viInputLimiters[2],
                    $viInputLimiters[3],
                    $viInputLimiters[4],
                    $viInputLimiters[5]
                );

                if ($bDistribution) {

                    $iSettingsIteration++;

                    if ($iSettingsIteration === $iIteration) {

                        return $this->setSettingsRanges(
                            $jLooperSettings,
                            $viInputLimiters[0],
                            $viInputLimiters[1],
                            $viInputLimiters[2],
                            $viInputLimiters[3],
                            $viInputLimiters[4],
                            $viInputLimiters[5]
                        );
                    }
                }
            }
        }

        throw new Exception("Ranges settings loop error.");
    }

    private function setInputLimiters(int $iRangeChange, int $iInputChange): array
    {
        $viInputLimiters = [];

        $viInputLimiters[0] = $this->iTrainInitInputBegin;
        $viInputLimiters[1] = $this->iTrainInitInputEnd;

        if ($this->sTrainInputShiftActive === 'yes') {
            $viInputLimiters[0] = $this->iTrainInitInputBegin + $iRangeChange + $iInputChange;
            $viInputLimiters[1] = $this->iTrainInitInputEnd + $iRangeChange + $iInputChange;
        }


        $viInputLimiters[2] = $this->iTestInitInputBegin;
        $viInputLimiters[3] = $this->iTestInitInputEnd;

        if ($this->sTestInputShiftActive === 'yes') {
            $viInputLimiters[2] = $this->iTestInitInputBegin + $iRangeChange + $iInputChange;
            $viInputLimiters[3] = $this->iTestInitInputEnd + $iRangeChange + $iInputChange;
        }


        $viInputLimiters[4] = $this->iPredictInitInputBegin;
        $viInputLimiters[5] = $this->iPredictInitInputEnd;

        if ($this->sPredictInputShiftActive === 'yes') {
            $viInputLimiters[4] = $this->iPredictInitInputBegin + $iRangeChange + $iInputChange;
            $viInputLimiters[5] = $this->iPredictInitInputEnd + $iRangeChange + $iInputChange;
        }

        return $viInputLimiters;
    }

    private function isValidDistribution(
            int $iTrainInputBegin,
            int $iTrainInputEnd,
            int $iTestInputBegin,
            int $iTestInputEnd,
            int $iPredictInputBegin,
            int $iPredictInputEnd
    ): bool {
        if ($iTrainInputBegin > $iTrainInputEnd || $iTrainInputBegin < $this->iScopeBegin ) {
            return false;
        }
        if ($iTrainInputEnd > $this->iScopeEnd) {
            return false;
        }


        if ($iTestInputBegin > $iTestInputEnd || $iTestInputBegin < $iTrainInputBegin) {
            return false;
        }
        if ($iTestInputEnd < $iTrainInputEnd || $iTestInputEnd > $this->iScopeEnd) {
            return false;
        }


        if ($iPredictInputBegin > $iPredictInputEnd || $iPredictInputBegin < $iTestInputBegin) {
            return false;
        }
        if ($iPredictInputEnd < $iTestInputEnd || $iPredictInputEnd > $this->iScopeEnd) {
            return false;
        }

        return true;
    }

    private function setSettingsRanges(
            array $jLooperSettings,
            int $iTrainInputBegin,
            int $iTrainInputEnd,
            int $iTestInputBegin,
            int $iTestInputEnd,
            int $iPredictInputBegin,
            int $iPredictInputEnd
    ): array {
        $jLooperSettings['train_input_begin'] = $iTrainInputBegin;
        $jLooperSettings['train_input_end'] = $iTrainInputEnd;

        $jLooperSettings['test_input_begin'] = $iTestInputBegin;
        $jLooperSettings['test_input_end'] = $iTestInputEnd;

        $jLooperSettings['predict_input_begin'] = $iPredictInputBegin;
        $jLooperSettings['predict_input_end'] = $iPredictInputEnd;

        return $jLooperSettings;
    }
}
