<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Settings;

use Exception;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Helper\ColumnCombinationsSettingsHelper;

class ColumnCombinationsSettings extends AbstractSettings implements SettingsInterface
{
    protected int $iN;

    protected array $viNumberOfCombinationTypes;

    public function __construct(
        protected ColumnCombinationsSettingsHelper $helper
    ) {
    }

    public function getSettingsIterationsNumber(array $joLooperConfig): int
    {
        $this->iN = (int) $joLooperConfig['n'];

        $settingsIterationsNumber = 0;

        $this->viNumberOfCombinationTypes[0] = 0;

        for ($iK = 1; $iK < $this->iN + 1; $iK++) {
            $settingsIterationsNumber += $this->helper->setCombinationsNumber($this->iN, $iK);

            $this->viNumberOfCombinationTypes[$iK] = $settingsIterationsNumber;
        }

        return $settingsIterationsNumber;
    }
    /**
     * @throws Exception
     */
    public function setOLooperSettings(
        int $iIteration,
        array $joLooperConfig,
        array $hmOptimizationSettings
    ): array {
        $this->iIteration = $iIteration;

        $this->helper->setCombinationParameters($iIteration, $this->viNumberOfCombinationTypes);

        $iCombination = $this->helper->getICombination();
        $iK = $this->helper->getIK();

        $jaCombination = $this->helper->setCombination($this->iN, $iK, $iIteration, $iCombination);

        $joSettings['train_input_column_keys'] = $jaCombination;
        $joSettings['train_target_column_keys'] = $joLooperConfig['train_target_column_keys'];
        $joSettings['test_input_column_keys'] = $jaCombination;
        $joSettings['test_target_column_keys'] = $joLooperConfig['test_target_column_keys'];
        $joSettings['predict_input_column_keys'] = $jaCombination;
        $joSettings['predict_target_column_keys'] = $joLooperConfig['predict_target_column_keys'];

        return $joSettings;
    }
}
