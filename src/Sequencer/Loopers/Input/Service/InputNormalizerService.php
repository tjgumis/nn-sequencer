<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Service;

use Exception;
use Paneric\NNOptimizer\Core\Traits\ArrayTrait;
use Paneric\NNOptimizer\Sequencer\Loopers\Input\Helper\InputNormalizerHelper;

class InputNormalizerService
{
    use ArrayTrait;

    private string $sNormalizationMode;
    private float $dNormalizationCoefficient;
    private float $dMinDeterminationCoefficient;
    private float $dScaleCoefficient;

    private array $vdMaxAbsValues;

    public function __construct(
        protected InputNormalizerHelper $inputNormalizerHelper,
        protected QuickSortService $quickSortService
    ) {
    }

    public function init(array $joLooperSettings): void
    {
        $this->sNormalizationMode = $joLooperSettings['normalization_mode'];

        $this->dNormalizationCoefficient = (float) $joLooperSettings['normalization_coefficient'];
        $this->dMinDeterminationCoefficient = (float) $joLooperSettings['min_determination_coefficient'];
        $this->dScaleCoefficient = (float) $joLooperSettings['scale_coefficient'];
    }
    /**
     * @throws Exception
     */
    public function normalize(array $a2d, array $a2iRanges): array
    {
        $a2dT = $this->adTranspose($a2d);

        $jL = count($a2dT);

        $this->vdMaxAbsValues = [];

        for ($j = 0; $j < $jL; $j++) {
            $dMaxAbsValue = 0.0;

            $ilProcess = count($a2iRanges);

            for ($iProcess = 0; $iProcess < $ilProcess; $iProcess++) {
                $iL = $a2iRanges[$iProcess][1] - $a2iRanges[$iProcess][0] + 1;

                $vd0 = [];

                for ($i = 0; $i < $iL; $i++) {
                    $vd0[$i] = $i;
                }

                $a2Vd[0] = $vd0;

                $a2Vd[1] = $this->copyOfRange($a2dT[$j], $a2iRanges[$iProcess][0], $a2iRanges[$iProcess][1]);

                $a2Vd = $this->quickSortService->sort($a2Vd, 1, 0);

                if ($iProcess !== $ilProcess - 1) {

                    $iRoot = $this->inputNormalizerHelper->setdRoot($a2Vd[1]);

                    $vdMean = $this->inputNormalizerHelper->setVdMean($a2Vd[1]);
                    $dStandardDeviation = $this->inputNormalizerHelper->setdStandardDeviation($a2Vd[1], $vdMean[1]);

                    $vdTemp = $this->inputNormalizerHelper->filterByStandardDeviation(
                        $a2Vd[1],
                        $this->dNormalizationCoefficient,
                        $dStandardDeviation
                    );

                    $vdMean = $this->inputNormalizerHelper->setVdMean($vdTemp);
                    $vdLinearEquationParameters = $this->inputNormalizerHelper->setLinearEquationParameters(
                        $vdTemp,
                        $vdMean,
                        $iRoot
                    );

                    $a2Vd[1] = $this->inputNormalizerHelper->setNormalized(
                        count($a2Vd[1]),
                        $vdLinearEquationParameters
                    );

                    $dMaxAbsValue = $this->dNormalizationCoefficient * $dStandardDeviation;
                }

                if ($iProcess === $ilProcess - 2) {
                    $this->vdMaxAbsValues[$j] = (float) number_format($dMaxAbsValue, 14, '.', '');
                }

                if ($iProcess === $ilProcess - 1) {
                    $a2Vd[1] = $this->inputNormalizerHelper->adaptByMaxValue($a2Vd[1], $dMaxAbsValue);
                }

                $a2Vd[1] = $this->inputNormalizerHelper->setScaled($a2Vd[1], $this->dScaleCoefficient, $dMaxAbsValue);

                $a2Vd = $this->quickSortService->sort($a2Vd, 0, 1);

                $a2dT[$j] = $this->arrayCopy($a2Vd [1], 0, $a2dT[$j], $a2iRanges[$iProcess][0], $iL);
            }
        }

        return $this->adTranspose($a2dT);
    }

    public function getVdMaxAbsValues(): array
    {
        return $this->vdMaxAbsValues;
    }
}
