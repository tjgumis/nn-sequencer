<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Settings;

use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Core\Traits\FileTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Service\InputBinarizeService;

class SourceFixedSettings extends AbstractSettings implements SettingsInterface
{
    use FileTrait;
    use ArrayTrait;

    public function __construct(
        protected InputBinarizeService $serviceInputBinarize
    ) {
    }

    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->iIteration = $iIteration;

        $joLooperSettings = $joLooperConfig['loops'][$iIteration];
        $vsBinary = [];
        $vsBinary[0] = $joLooperSettings['input_binary'];
        $vsBinary[1] = $joLooperSettings['target_binary'];

        $joGeneralSettings = $hmOptimizationSettings['general_looper'];
        $sSeparator = $joGeneralSettings['separator'];

        $joScopeSettings = $hmOptimizationSettings['input_scope_looper'];
        $sScopeFolder = $joScopeSettings['scope_folder'];

        $joTransformationSettings = $hmOptimizationSettings['input_transformation_looper'];
        $sRangeFolder = $joTransformationSettings['range_folder'];

        $sNormalizedScopeFileName = $sScopeFolder . $joGeneralSettings['normalized_scope_file_name'];


        $a2sFileNames [0][0] = $sRangeFolder . $joGeneralSettings['train_input_file_name'];
        $a2sFileNames [0][1] = $sRangeFolder . $joGeneralSettings['train_target_file_name'];

        $a2sFileNames [1][0] = $sRangeFolder . $joGeneralSettings['test_input_file_name'];
        $a2sFileNames [1][1] = $sRangeFolder . $joGeneralSettings['test_target_file_name'];

        $a2sFileNames [2][0] = $sRangeFolder . $joGeneralSettings['predict_input_file_name'];
        $a2sFileNames [2][1] = $sRangeFolder . $joGeneralSettings['predict_target_file_name'];


        $a2iRanges [0][0] = (int) $joScopeSettings['train_input_begin'];
        $a2iRanges [0][1] = (int) $joScopeSettings['train_input_end'];

        $a2iRanges [1][0] = (int) $joScopeSettings['test_input_begin'];
        $a2iRanges [1][1] = (int) $joScopeSettings['test_input_end'];

        $a2iRanges [2][0] = (int) $joScopeSettings['predict_input_begin'];
        $a2iRanges [2][1] = (int) $joScopeSettings['predict_input_end'];


        $iL = count($a2sFileNames);

        for ($i = 0; $i < $iL; $i++) {

            $a2d = $this->get2DoubleArrayPartially($sNormalizedScopeFileName, $a2iRanges[$i][0], $a2iRanges[$i][1]);

            $this->writeA2d(
                $this->binarizeA2d($a2d, $vsBinary[0]),
                $sSeparator,
                $a2sFileNames[$i][0]
            );
            $this->writeA2d(
                $this->binarizeA2d($a2d, $vsBinary[1]),
                $sSeparator,
                $a2sFileNames[$i][1]
            );
        }

        return $joLooperSettings;
    }

    private function binarizeA2d(array $a2d, string $sBinarize): array
    {
        $iL = count($a2d);
        $jL = count($a2d[0]);

        $a2dB = $this->initArray('float', $iL, $jL);

        for ($i = 0; $i < $iL; $i++) {
            $a2dB[$i] = $this->arrayCopy($a2d[$i], 0, $a2dB[$i], 0, $jL);
        }

        if ($sBinarize === 'yes') {
            $a2dB = $this->serviceInputBinarize->binarize($a2dB);
        }

        return $a2dB;
    }
}
