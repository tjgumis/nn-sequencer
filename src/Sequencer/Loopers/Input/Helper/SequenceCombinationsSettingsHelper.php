<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Helper;

use Exception;

class SequenceCombinationsSettingsHelper
{
    private const SEQUENCE_DELAY_MIN = 0;
    private const SEQUENCE_ROWS_NUMBER_MIN = 1;
    private const SEQUENCE_SHIFT_MIN = 1;

    private int $iTrainInputRowsNumber;

    private int $iDelayInit;
    private int $iDelayStart;
    private int $iDelayStop;

    private int $iRowsNumberInit;
    private int $iRowsNumberStart;
    private int $iRowsNumberStop;

    private int $iShiftInit;
    private int $iShiftStart;
    private int $iShiftStop;

    private int $iSavedIteration;

    private array $joLooperSettings;

    public function setITrainInputRowsNumber(int $iTrainInputRowsNumber): void
    {
        $this->iTrainInputRowsNumber = $iTrainInputRowsNumber;
    }

    public function initParameters(array $joLooperConfig): void
    {
        $this->iSavedIteration = -1;

        $this->iDelayInit = (int) $joLooperConfig['delay_start'];
        $this->iDelayStart = $this->iDelayInit;
        $this->iDelayStop = (int) $joLooperConfig['delay_stop'];

        $this->iRowsNumberInit = (int) $joLooperConfig['rows_number_start'];
        $this->iRowsNumberStart = $this->iRowsNumberInit;
        $this->iRowsNumberStop = (int) $joLooperConfig['rows_number_stop'];

        $this->iShiftInit = (int) $joLooperConfig['shift_start'];
        $this->iShiftStart = $this->iShiftInit;
        $this->iShiftStop = (int) $joLooperConfig['shift_stop'];

        $this->joLooperSettings = [];
        $this->joLooperSettings['input_sequencer_marker'] = $joLooperConfig['input_sequencer_marker'];
        $this->joLooperSettings['rows_number'] = 0;
        $this->joLooperSettings['shift'] = 0;
        $this->joLooperSettings['delay'] = 0;
        $this->joLooperSettings['target_type'] = $joLooperConfig['target_type'];
    }
    /**
     * @throws Exception
     */
    public function validateParameters(): void
    {
        if (
            $this->iTrainInputRowsNumber <
            self::SEQUENCE_DELAY_MIN + self::SEQUENCE_ROWS_NUMBER_MIN + self::SEQUENCE_SHIFT_MIN
        ) {
            throw new Exception(sprintf(
                "%s\n%s\n%s\n%s\n%s\n",
                "iTrainInputRowsNumber < SEQUENCE_DELAY_MIN + SEQUENCE_ROWS_NUMBER_MIN + SEQUENCE_SHIFT_MIN",
                "iTrainInputRowsNumber = " . $this->iTrainInputRowsNumber,
                "SEQUENCE_DELAY_MIN = " . self::SEQUENCE_DELAY_MIN,
                "SEQUENCE_ROWS_NUMBER_MIN = " . self::SEQUENCE_ROWS_NUMBER_MIN,
                "SEQUENCE_SHIFT_MIN = " . self::SEQUENCE_SHIFT_MIN
            ));
        }

        if (!((self::SEQUENCE_DELAY_MIN <= $this->iDelayStart) && ($this->iDelayStart <= $this->iDelayStop))) {
            throw new Exception(sprintf(
                "%s%s%s%s",
                "!((SEQUENCE_DELAY_MIN <= iDelayStart) && (iDelayStart <= iDelayStop))",
                "SEQUENCE_DELAY_MIN = " . self::SEQUENCE_DELAY_MIN,
                "iDelayStart = " . $this->iDelayStart,
                "iDelayStop = " . $this->iDelayStop
            ));
        }

        $iDelayMax = $this->iTrainInputRowsNumber - self::SEQUENCE_ROWS_NUMBER_MIN - self::SEQUENCE_SHIFT_MIN;

        if ($this->iDelayStop > $iDelayMax) {
            throw new Exception(sprintf(
                "%s\n%s\n%s\n",
                "iDelayStop > iDelayMax",
                "iDelayStop = " . $this->iDelayStop,
                "iDelayMax = " . $iDelayMax
            ));
        }


        if (!(
            (self::SEQUENCE_ROWS_NUMBER_MIN <= $this->iRowsNumberStart) &&
            ($this->iRowsNumberStart <= $this->iRowsNumberStop)
        )) {
            throw new Exception(sprintf(
                "%s\n%s\n%s\n%s\n",
                "!((SEQUENCE_ROWS_NUMBER_MIN <= iRowsNumberStart) && (iRowsNumberStart <= iRowsNumberStop))",
                "SEQUENCE_ROWS_NUMBER_MIN = " . self::SEQUENCE_ROWS_NUMBER_MIN,
                "iRowsNumberStart = " . $this->iRowsNumberStart,
                "iRowsNumberStop = " . $this->iRowsNumberStop
            ));
        }

        $iRowsNumberMax = $this->iTrainInputRowsNumber - self::SEQUENCE_DELAY_MIN - self::SEQUENCE_SHIFT_MIN;

        if ($this->iRowsNumberStop > $iRowsNumberMax) {
            throw new Exception(sprintf(
                "%s\n%s\n%s\n",
                "iRowsNumberStop > iRowsNumberMax",
                "iRowsNumberStop = " . $this->iRowsNumberStop,
                "iRowsNumberMax = " . $iRowsNumberMax
            ));
        }


        if (!((self::SEQUENCE_SHIFT_MIN <= $this->iShiftStart) && ($this->iShiftStart <= $this->iShiftStop))) {
            throw new Exception(sprintf(
                "%s\n%s\n%s\n%s\n",
                "!((SEQUENCE_SHIFT_MIN <= iShiftStart) && (iShiftStart <= iShiftStop))",
                "SEQUENCE_SHIFT_MIN = " . self::SEQUENCE_SHIFT_MIN,
                "iShiftStart = " . $this->iShiftStart,
                "iShiftStop = " . $this->iShiftStop
            ));
        }

        $iShiftMax = $this->iTrainInputRowsNumber - self::SEQUENCE_DELAY_MIN - self::SEQUENCE_ROWS_NUMBER_MIN;

        if ($this->iShiftStop > $iShiftMax) {
            throw new Exception(sprintf(
                "%s\n%s\n%s\n",
                "iShiftStart > iShiftMax",
                "iShiftStart = " . $this->iShiftStop,
                "iShiftMax = " . $iShiftMax
            ));
        }
    }

    public function setIIterationParameters(): void
    {
        for ($iDelay = $this->iDelayStart; $iDelay < $this->iDelayStop + 1; $iDelay++) {
            for ($iRowsNumber = $this->iRowsNumberStart; $iRowsNumber < $this->iRowsNumberStop + 1; $iRowsNumber++) {
                for ($iShift = $this->iShiftStart; $iShift < $this->iShiftStop + 1; $iShift++) {
                    $bFound = $iDelay + $iRowsNumber + $iShift < $this->iTrainInputRowsNumber + 1;

                    $this->setIterationStartingValues($iDelay, $iRowsNumber, $iShift);

                    $this->setOLooperSettings($iDelay, $iRowsNumber, $iShift);

                    if ($bFound) {
                        $this->iSavedIteration++;

                        return;
                    }
                }
            }
        }

        $this->iSavedIteration = -1;
    }

    private function setIterationStartingValues(int $iDelay, int $iRowsNumber, int $iShift): void
    {
        if ($iShift < $this->iShiftStop) {
            $this->iDelayStart = $iDelay;
            $this->iRowsNumberStart = $iRowsNumber;
            $this->iShiftStart = $iShift + 1;
        }

        if ($iRowsNumber < $this->iRowsNumberStop && $iShift === $this->iShiftStop) {
            $this->iDelayStart = $iDelay;
            $this->iRowsNumberStart = $iRowsNumber + 1;
            $this->iShiftStart = $this->iShiftInit;
        }

        if ($iRowsNumber === $this->iRowsNumberStop && $iShift === $this->iShiftStop) {
            $this->iDelayStart = $iDelay + 1;
            $this->iRowsNumberStart = $this->iRowsNumberInit;
            $this->iShiftStart = $this->iShiftInit;
        }
    }

    private function setOLooperSettings(int $iDelay, int $iRowsNumber, int $iShift): void
    {
        $this->joLooperSettings['delay'] = $iDelay;
        $this->joLooperSettings['rows_number'] = $iRowsNumber;
        $this->joLooperSettings['shift'] = $iShift;
    }

    public function setParameters(): void
    {
        $this->iDelayStart = $this->iDelayInit;
        $this->iRowsNumberStart = $this->iRowsNumberInit;
        $this->iShiftStart = $this->iShiftInit;
    }

    public function getISavedIteration(): int
    {
        return $this->iSavedIteration;
    }

    public function getOLooperSettings(): array
    {
        return $this->joLooperSettings;
    }
}
