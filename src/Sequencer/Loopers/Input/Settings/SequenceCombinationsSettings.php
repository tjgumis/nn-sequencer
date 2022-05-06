<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Settings;

use Exception;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Helper\SequenceCombinationsSettingsHelper;

class SequenceCombinationsSettings extends AbstractSettings implements SettingsInterface
{
    private bool $parametersChecked;

    public function __construct(
        protected SequenceCombinationsSettingsHelper $sequenceCombinationsSettingsHelper
    ) {
    }

    public function getSettingsIterationsNumber(array $joLooperConfig): int
    {
        $this->parametersChecked = false;

        return -1;
    }
    /**
     * @throws Exception
     */
    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        if (!$this->parametersChecked) {
            $joScopeSettings = $hmOptimizationSettings['input_scope_looper'];

            $this->sequenceCombinationsSettingsHelper->setiTrainInputRowsNumber(
                $this->setiInputRowsNumber($joScopeSettings)
            );
            $this->sequenceCombinationsSettingsHelper->initParameters($joLooperConfig);
            $this->sequenceCombinationsSettingsHelper->validateParameters();

            $this->parametersChecked = true;
        }

        $this->iIteration = -1;

        $this->sequenceCombinationsSettingsHelper->setiIterationParameters();

        $iSavedIteration = $this->sequenceCombinationsSettingsHelper->getiSavedIteration();

        if ($iSavedIteration !== -1) {
            $this->iIteration = -2;
        }

        if ($iSavedIteration === -1) {
            $this->sequenceCombinationsSettingsHelper->setParameters();
        }

        return $this->sequenceCombinationsSettingsHelper->getOLooperSettings();
    }

    private function setIInputRowsNumber(array $joScopeSettings): int
    {
        $iTrainInputBegin = (int) $joScopeSettings['train_input_begin'];
        $iTrainInputEnd = (int) $joScopeSettings['train_input_end'];

        return $iTrainInputEnd - $iTrainInputBegin + 1;
    }
}
