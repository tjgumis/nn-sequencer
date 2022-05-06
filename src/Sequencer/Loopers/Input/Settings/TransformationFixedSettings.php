<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Settings;

use Exception;
use Paneric\NNOptimizer\Core\Traits\FileTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Service\InputGradatorService;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Service\InputNormalizerService;

class TransformationFixedSettings extends AbstractSettings implements SettingsInterface
{
    use FileTrait;

    public function __construct(
        protected InputNormalizerService $inputNormalizerService,
        protected InputGradatorService   $inputGradatorService
    ) {
    }

    /**
     * @throws Exception
     */
    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->iIteration = $iIteration;

        $joLooperSettings = $joLooperConfig['loops'][$iIteration];
        $sRangesProcessing = $joLooperSettings['ranges_processing'];

        $joGeneralSettings = $hmOptimizationSettings['general_looper'];
        $sSeparator = $joGeneralSettings['separator'];

        $joScopeSettings = $hmOptimizationSettings['input_scope_looper'];
        $sScopeFolder = $joScopeSettings['scope_folder'];

        $sScopeFileName = $sScopeFolder . $joGeneralSettings['scope_file_name'];
        $sNormalizedScopeFileName = $sScopeFolder . $joGeneralSettings['normalized_scope_file_name'];

        $a2iRanges = [];
        if ($sRangesProcessing === 'Train_TestPredict') {
            $a2iRanges = $this->setA2iRangesForTr_TePrProcessing($joScopeSettings);
        }
        if ($sRangesProcessing === 'TrainTest_Predict') {
            $a2iRanges = $this->setA2iRangesForTrTe_PrProcessing($joScopeSettings);
        }
        if ($sRangesProcessing === 'Train_Test_Predict') {
            $a2iRanges = $this->setA2iRangesForTr_Te_PrProcessing($joScopeSettings);
        }

        $sRangeFolder = $sScopeFolder . $this->setsRangeFolder($joScopeSettings);
        $this->createDir($sRangeFolder);
        $joLooperSettings['range_folder'] = $sRangeFolder;

        $sResultsFolder = $sRangeFolder . 'results/';
        $this->createDir($sResultsFolder);
        $joLooperSettings['results_folder'] = $sResultsFolder;

        $a2d = $this->get2DoubleArray($sScopeFileName);

        $sNormalized = $joLooperSettings['normalized'];
        if ($sNormalized === 'yes') {
            $this->inputNormalizerService->init($joLooperSettings);

            $a2d = $this->inputNormalizerService->normalize($a2d, $a2iRanges);

            $joLooperSettings['max_abs_values'] = $this->inputNormalizerService->getVdMaxAbsValues();
        }

        $iGradationCoefficient = (int) $joLooperSettings['gradation_coefficient'];
        if ($iGradationCoefficient > 0 && $sNormalized === 'yes') {
            $a2d = $this->inputGradatorService->gradate($a2d, $iGradationCoefficient);
        }

        $this->writeA2d($a2d, $sSeparator, $sNormalizedScopeFileName);

        return $joLooperSettings;
    }

    private function setA2iRangesForTr_TePrProcessing(array $joLooperSettings): array
    {
        $a2iRanges = [];

        $a2iRanges [0][0] = (int) $joLooperSettings['train_input_begin'];
        $a2iRanges [0][1] = (int) $joLooperSettings['train_input_end'];

        $a2iRanges [1][0] = (int) $joLooperSettings['test_input_begin'];
        $a2iRanges [1][1] = (int) $joLooperSettings['predict_input_end'];

        return $a2iRanges;
    }

    private function setA2iRangesForTrTe_PrProcessing(array $joLooperSettings): array
    {
        $a2iRanges = [];

        $a2iRanges [0][0] = (int) $joLooperSettings['train_input_begin'];
        $a2iRanges [0][1] = (int) $joLooperSettings['test_input_end'];

        $a2iRanges [1][0] = (int) $joLooperSettings['predict_input_begin'];
        $a2iRanges [1][1] = (int) $joLooperSettings['predict_input_end'];

        return $a2iRanges;
    }

    private function setA2iRangesForTr_Te_PrProcessing(array $joLooperSettings): array
    {
        $a2iRanges = [];

        $a2iRanges [0][0] = (int) $joLooperSettings['train_input_begin'];
        $a2iRanges [0][1] = (int) $joLooperSettings['train_input_end'];

        $a2iRanges [1][0] = (int) $joLooperSettings['test_input_begin'];
        $a2iRanges [1][1] = (int) $joLooperSettings['test_input_end'];

        $a2iRanges [2][0] = (int) $joLooperSettings['predict_input_begin'];
        $a2iRanges [2][1] = (int) $joLooperSettings['predict_input_end'];

        return $a2iRanges;
    }

    private function setsRangeFolder(array $joLooperSettings): string
    {
        return sprintf(
                "%s_%s_%s_%s_%s_%s/",
                $joLooperSettings['train_input_begin'],
                $joLooperSettings['train_input_end'],
                $joLooperSettings['test_input_begin'],
                $joLooperSettings['test_input_end'],
                $joLooperSettings['predict_input_begin'],
                $joLooperSettings['predict_input_end']
        );
    }
}
