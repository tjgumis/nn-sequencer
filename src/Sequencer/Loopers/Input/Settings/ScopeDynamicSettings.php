<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Settings;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Core\Traits\FileTrait;
use Paneric\NNOptimizer\Sequencer\Abstracts\AbstractSettings;
use Paneric\NNOptimizer\Sequencer\Interfaces\SettingsInterface;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Service\ScopeRangesModifierService;

class ScopeDynamicSettings extends AbstractSettings implements SettingsInterface
{
    use FileTrait;
    use ArrayTrait;

    public function __construct(
        protected ScopeRangesModifierService $scopeRangesModifierService
    ) {
    }
    /**
     * @throws Exception
     */
    public function getSettingsIterationsNumber(array $joLooperConfig): int
    {
        $this->scopeRangesModifierService->init($joLooperConfig);

        return $this->scopeRangesModifierService->getSettingsIterationsNumber();
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

        $iScopeBegin = $joLooperConfig['scope_begin'];
        $iScopeEnd = $joLooperConfig['scope_end'];

        $joLooperSettings['scope_begin'] = $iScopeBegin;
        $joLooperSettings['scope_end'] = $iScopeEnd;

        $joLooperSettings = $this->scopeRangesModifierService->setIterationRanges($iIteration, $joLooperSettings);


        $joGeneralSettings = $hmOptimizationSettings['general_looper'];

        $uploadFolder = $joGeneralSettings['upload_folder'];
        $sSeparator = $joGeneralSettings['separator'];


        $sScopeFolder = $uploadFolder . $this->setSScopeFolder($joLooperSettings);
        $joLooperSettings['scope_folder'] = $sScopeFolder;


        $sSourceFileName = $uploadFolder . $joGeneralSettings['source_file_name'];
        $sScopeFileName = $sScopeFolder . $joGeneralSettings['scope_file_name'];


        $a2dSource = $this->get2DoubleArray($sSourceFileName);

        $a2dScope = $this->copyA2dInRowsRange($a2dSource, $iScopeBegin, $iScopeEnd);

        $this->createDir($sScopeFolder);

        $this->writeA2d($a2dScope, $sSeparator, $sScopeFileName);

        return $joLooperSettings;
    }

    private function setSScopeFolder(array $joLooperSettings): string
    {
        return sprintf(
                "%s_%s/",
                $joLooperSettings['scope_begin'],
                $joLooperSettings['scope_end']
        );
    }
}
