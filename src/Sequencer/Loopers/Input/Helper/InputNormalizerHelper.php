<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Helper;

use Exception;
use RuntimeException;

class InputNormalizerHelper
{
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setDRoot(array $vd): int
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead of int|float value.');
        }

        $iL = count($vd);

        for ($i = 0; $i < $iL - 1; $i++) {
            if ($vd[$i] === 0.0) {
                return $i;
            }

            if ($vd[$i] < 0 && $vd[$i + 1] > 0) {
                if (abs($vd[$i]) < abs($vd[$i + 1])) {
                    return $i;
                }

                return $i + 1;
            }
        }

        throw new RuntimeException();
    }

    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setVdMean(array $vd): array
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $vdMean = [0.0, 0.0];

        $dX = -1;

        foreach ($vd as $v) {
            $dX++;
            $vdMean[0] += $dX;
            $vdMean[1] += $v;
        }

        $vdMean[0] /= count($vd);
        $vdMean[1] /= count($vd);

        return $vdMean;
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setDStandardDeviation(array $vd, float $dMeanY): float
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $dStandardDeviation = 0.0;

        foreach ($vd as $v) {
            $dStandardDeviation += ($v - $dMeanY) * ($v - $dMeanY);
        }

        return sqrt($dStandardDeviation / (count($vd) - 1));
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function filterByStandardDeviation(
        array $vd,
        float $dNormalizationCoefficient,
        float $dStandardDeviation
    ): array {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $dl = [];

        $ii = -1;

        foreach ($vd as $v) {

            if (abs($v) < $dNormalizationCoefficient * $dStandardDeviation) {

                $ii++;
                $dl[$ii] = $v;
            }
        }

        $vdf = [];

        $iL = count($dl);

        for ($i = 0; $i < $iL; $i++) {

            $vdf[$i] = $dl[$i];
        }

        return $vdf;
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function adaptByMaxValue(array $vd, float $dMaxAbsValue): array
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $iL = count($vd);

        for ($i = 0; $i < $iL; $i++) {
            if (abs($vd[$i]) > $dMaxAbsValue) {
                if ($vd[$i] > 0) {
                    $vd[$i] = $dMaxAbsValue;
                }
                if ($vd[$i] < 0) {
                    $vd[$i] = -$dMaxAbsValue;
                }
            }
        }

        return $vd;
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setLinearEquationParameters(array $vd, array $vdMean, int $iRoot): array
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $iL = count($vd);

        $m1 = 0.0;
        $m2 = 0.0;

        for ($i = 0; $i < $iL; $i++) {
            $m1 += ($i - $vdMean[0]) * ($vd[$i] - $vdMean[1]);
            $m2 += ($i - $vdMean[0]) * ($i - $vdMean[0]);
        }

        $vdParameters = [];
        $vdParameters[0] = $m1 / $m2;
        $vdParameters[1] = -$vdParameters[0] * $iRoot;

        return $vdParameters;
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setNormalized(int $iL, array $vd): array
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $vdN = [];

        for ($i = 0; $i < $iL; $i++) {
            $vdN[$i] = $vd[0] *  $i + $vd[1];
        }

        return $vdN;
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setMaxAbsoluteValue(array $vd): float
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $iL = count($vd);

        if (abs($vd[0]) < abs($vd[$iL - 1])) {
            return abs($vd[$iL - 1]);
        }

        return abs($vd[0]);
    }
    /**
     * @param int[]|float[] $vd
     *
     * @throws Exception
     */
    public function setScaled(array $vd, float $dScaleCoefficient, float $dMaxAbsValue): array
    {
        if (!empty($vd) && is_string($vd[0])) {
            throw new Exception('String instead int|float value.');
        }

        $iL = count($vd);

        for ($i = 0; $i < $iL; $i++) {
            $vd[$i] = $vd[$i] / $dMaxAbsValue * $dScaleCoefficient;
        }

        return $vd;
    }
}
