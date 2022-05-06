<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Helper;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;

class NetworkResultsHelper
{
    use ArrayTrait;

    public function composeForEpoch(
        int $idxInputSet,
        int $idxSetRow,
        int $iSequenceResultsRowsNumber,
        array $vdTargetRow,
        array $a2dFnc,
        array $a2dEpochResults,
        float $dASE
    ): array {
        $iTargetsNumber = count($vdTargetRow);
        $iLayersNumber = count($a2dFnc);

        for ($iTarget = 0; $iTarget < $iTargetsNumber; $iTarget++) {

            $i = ($idxInputSet * $iSequenceResultsRowsNumber + $idxSetRow) * 2;

            $dOutput = $a2dFnc[$iLayersNumber - 1][$iTarget + 1];
            $dTarget = $vdTargetRow[$iTarget];

            $a2dEpochResults[$i][$iTarget] = $a2dFnc[$iLayersNumber - 1][$iTarget + 1];
            $a2dEpochResults[$i + 1][$iTarget] = $vdTargetRow[$iTarget];

            $dAE = $dOutput - $dTarget;

            $dASE = $dASE + $dAE * $dAE;
        }

        $epochResults = [];

        $epochResults['dASE'] = $dASE;
        $epochResults['a2dEpochResults'] = $a2dEpochResults;

        return $epochResults;
    }
    /**
     * @throws Exception
     */
    public function updateProcess(
            string $mode,
            int $iResultsNumber,
            array $vldResultsMSE,
            array $vla2dResultsValues,
            float $dEpochMSE,
            array $a2dEpochResults
    ): array {
        $indexOfMinMSE = -1;
        $indexOfMaxMSE = -1;

        if (!empty($vldResultsMSE)) {
            $indexOfMinMSE = $this->indexOfMinVecValue($vldResultsMSE);
            $indexOfMaxMSE = $this->indexOfMaxVecValue($vldResultsMSE);
        }

        if (($indexOfMinMSE >= $iResultsNumber) || ($indexOfMaxMSE >= $iResultsNumber)) {
            throw new Exception();
        }

        $processResults = [];

        if ($indexOfMinMSE === -1 && $indexOfMaxMSE === -1) {

            if ($mode === 'test') {
                echo ('dTestMSE = ' . $dEpochMSE . "\n");
            }

            $indexOfMSE = 0;

            $vldResultsMSE[$indexOfMSE] = $dEpochMSE;
            $vla2dResultsValues[$indexOfMSE] = $a2dEpochResults;

            $processResults['vldResultsMSE'] = $vldResultsMSE;
            $processResults['vla2dResultsValues'] = $vla2dResultsValues;
            $processResults['indexOfMSE'] = 0;

            return  $processResults;
        }

        if (!empty($vldResultsMSE) && $vldResultsMSE[$indexOfMaxMSE] < $dEpochMSE) {

            if (count($vldResultsMSE) < $iResultsNumber) {

                if ($mode === 'test') {
                    echo('dTestMSE = ' . $dEpochMSE . "\n");
                }

                $indexOfMSE = count($vldResultsMSE);

                $vldResultsMSE[$indexOfMSE] = $dEpochMSE;
                $vla2dResultsValues[$indexOfMSE] = $a2dEpochResults;

                $processResults['vldResultsMSE'] = $vldResultsMSE;
                $processResults['vla2dResultsValues'] = $vla2dResultsValues;
                $processResults['indexOfMSE'] = $indexOfMSE;

                return  $processResults;
            }
        }

        if (!empty($vldResultsMSE) && $vldResultsMSE[$indexOfMaxMSE] > $dEpochMSE) {

            if (count($vldResultsMSE) === $iResultsNumber) {

                if ($mode === 'test') {
                    echo('dTestMSE = ' . $dEpochMSE .  "\n");
                }

                $indexOfMSE = $indexOfMaxMSE;

                $vldResultsMSE[$indexOfMSE] = $dEpochMSE;
                $vla2dResultsValues[$indexOfMSE] = $a2dEpochResults;

                $processResults['vldResultsMSE'] = $vldResultsMSE;
                $processResults['vla2dResultsValues'] = $vla2dResultsValues;
                $processResults['indexOfMSE'] = $indexOfMSE;

                return  $processResults;
            }

            if ($mode === 'test') {
                echo('dTestMSE = ' . $dEpochMSE . "\n");
            }

            $indexOfMSE = count($vldResultsMSE);

            $vldResultsMSE[$indexOfMSE] = $dEpochMSE;
            $vla2dResultsValues[$indexOfMSE] = $a2dEpochResults;

            $processResults['vldResultsMSE'] = $vldResultsMSE;
            $processResults['vla2dResultsValues'] = $vla2dResultsValues;
            $processResults['indexOfMSE'] = $indexOfMSE;

            return  $processResults;
        }

        $processResults['indexOfMSE'] = -1;

        return  $processResults;
    }
    /**
     * @throws Exception
     */
    public function updateSettings(
        string $mode,
        int $iResultsNumber,
        int $indexOfMSE,
        array $joCurrentSettings,
        array $vljoResultsSettings,
        array $a3dCurrentWeights,
        array $vla3dResultsWeights,
        array $vdCurrentAllMSE,
        array $vlvdResultsAllMSE
    ): array {
        $hmResultsSettings = [];

        if (($indexOfMSE < 0) || ($iResultsNumber < $indexOfMSE + 1)) {
            throw new Exception();
        }

        if ($indexOfMSE === 0 && count($vljoResultsSettings) === 0) {

            $vljoResultsSettings[$indexOfMSE] = $joCurrentSettings;
            $vla3dResultsWeights[$indexOfMSE] = $a3dCurrentWeights;
            $vlvdResultsAllMSE[$indexOfMSE] = $vdCurrentAllMSE;

            $hmResultsSettings['vlsSettings'] = $vljoResultsSettings;
            $hmResultsSettings['vla3dWeights'] = $vla3dResultsWeights;
            $hmResultsSettings['vlvdAllMSE'] = $vlvdResultsAllMSE;

            return  $hmResultsSettings;
        }

        if (count($vljoResultsSettings) === $iResultsNumber) {

            $vljoResultsSettings[$indexOfMSE] = $joCurrentSettings;
            $vla3dResultsWeights[$indexOfMSE] = $a3dCurrentWeights;
            $vlvdResultsAllMSE[$indexOfMSE] = $vdCurrentAllMSE;

            $hmResultsSettings['vlsSettings'] = $vljoResultsSettings;
            $hmResultsSettings['vla3dWeights'] = $vla3dResultsWeights;
            $hmResultsSettings['vlvdAllMSE'] = $vlvdResultsAllMSE;

            return  $hmResultsSettings;
        }

        if (count($vljoResultsSettings) < $iResultsNumber & $indexOfMSE === count($vljoResultsSettings)) {

            $vljoResultsSettings[$indexOfMSE] = $joCurrentSettings;
            $vla3dResultsWeights[$indexOfMSE] = $a3dCurrentWeights;
            $vlvdResultsAllMSE[$indexOfMSE] = $vdCurrentAllMSE;

            $hmResultsSettings['vlsSettings'] = $vljoResultsSettings;
            $hmResultsSettings['vla3dWeights'] = $vla3dResultsWeights;
            $hmResultsSettings['vlvdAllMSE'] = $vlvdResultsAllMSE;

            return  $hmResultsSettings;
        }

        throw new Exception();
    }
}
