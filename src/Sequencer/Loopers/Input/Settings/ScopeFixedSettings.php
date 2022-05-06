<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Settings;

use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Core\Traits\FileTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;

class ScopeFixedSettings extends AbstractSettings implements SettingsInterface
{
    use FileTrait;
    use ArrayTrait;

    public function setOLooperSettings(int $iIteration, array $joLooperConfig, array $hmOptimizationSettings): array
    {
        $this->iIteration = $iIteration;


        $joLooperSettings = $joLooperConfig['loops'][$iIteration];

        $iScopeBegin = (int) $joLooperSettings['scope_begin'];
        $iScopeEnd = (int) $joLooperSettings['scope_end'];

        $iTrainInputBegin = (int) $joLooperSettings['train_input_begin'];
        $iTrainInputEnd = (int) $joLooperSettings['train_input_end'];

        $joLooperSettings['train_input_rows_number'] = $iTrainInputEnd - $iTrainInputBegin + 1;


        $joGeneralSettings = $hmOptimizationSettings['general_looper'];

        $uploadFolder = $joGeneralSettings['upload_folder'];
        $sSeparator = $joGeneralSettings['separator'];


        $sScopeFolder = $uploadFolder . $this->setsScopeFolder($joLooperSettings);
        $joLooperSettings['scope_folder'] = $sScopeFolder;


        $sSourceFileName = $uploadFolder . $joGeneralSettings['source_file_name'];
        $sScopeFileName = $sScopeFolder . $joGeneralSettings['scope_file_name'];


        $a2dSource = $this->get2DoubleArray($sSourceFileName);

        $a2dScope = $this->copyA2dInRowsRange($a2dSource, $iScopeBegin, $iScopeEnd);

        $this->createDir($sScopeFolder);

        $this->writeA2d($a2dScope, $sSeparator, $sScopeFileName);

        return $joLooperSettings;
    }

    private function setsScopeFolder(array $joLooperSettings): string
    {
        return sprintf(
                "%s_%s/",
                $joLooperSettings['scope_begin'],
                $joLooperSettings['scope_end']
        );
    }
}
