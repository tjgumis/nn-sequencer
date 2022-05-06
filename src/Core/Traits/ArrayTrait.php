<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Core\Traits;

use Exception;

trait ArrayTrait
{
    protected function copyA2dInRowsRange(array $a2dBase, int $iRowsBegin, int $iRowsEnd): array
    {
        $a2d =[];

        $iRow = -1;

        for ($i = $iRowsBegin; $i < $iRowsEnd + 1; $i++) {
            $iRow++;
            $a2d[$iRow] = $a2dBase[$i];
        }

        return $a2d;
    }

    protected function arrayCopy(array $src, int $srcOffset, array $dest, int $destOffset, int $length): array
    {
        $iL = $srcOffset + $length;

        $destOffset--;

        for ($i = $srcOffset; $i < $iL; $i++) {
            $destOffset++;
            $dest[$destOffset] = $src[$i];
        }

        return $dest;
    }

    protected function copyOfRange(array $src, int $srcFrom, int $srcTo): array
    {
        $arr = [];

        for ($i = $srcFrom; $i < $srcTo + 1; $i++) {
            $arr[] = $src[$i];
        }
        return $arr;
    }

    public function adTranspose(array $a): array
    {
        $b = [];

        $m = count($a);
        $n = count($a[0]);

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $b[$j][$i] = $a[$i][$j];
            }
        }

        return $b;
    }
    /**
     * @param int[]|float[] $vec
     *
     * @throws Exception
     */
    public function maxIntVecValue(array $vec): int
    {
        if (!empty($vec) && is_string($vec[0])) {
            throw new Exception('String instead int|float value.');
        }

        $max = $vec[0];
        $iL = count($vec);
        for ($i = 1; $i < $iL; $i++){
            if ($vec[$i] > $max){
                $max = $vec[$i];
            }
        }

        return $max;
    }
    /**
     * @param int[]|float[] $vec
     *
     * @throws Exception
     */
    public function indexOfMaxVecValue(array $vec): ?int
    {
        if (!empty($vec) && is_string($vec[0])) {
            throw new Exception('String instead int|float value.');
        }

        $max = $vec[0];
        $indexMax = 0;

        $iL = count($vec);
        for ($i = 1; $i < $iL; $i++){
            if ($vec[$i] > $max){
                $indexMax = $i;
            }
        }

        return $indexMax;
    }

    /**
     * @param int[]|float[] $vec
     *
     * @throws Exception
     */
    public function indexOfMinVecValue(array $vec): ?int
    {
        if (!empty($vec) && is_string($vec[0])) {
            throw new Exception('String instead int|float value.');
        }

        $min = $vec[0];
        $indexMin = 0;

        $iL = count($vec);
        for ($i = 1; $i < $iL; $i++){
            if ($vec[$i] < $min){
                $indexMin = $i;
            }
        }

        return $indexMin;
    }

    public function maxViValue(array $vec): int
    {
        $max = $vec[0];

        $iL = count($vec);
        for ($i = 1; $i < $iL; $i++) {
            if ($vec[$i] > $max) {
                $max = $vec[$i];
            }
        }

        return $max;
    }

    public function getMaxPositiveIntValue(array $ja): int
    {
        $iMaxValue = 0;

        foreach ($ja as $oValue) {

            $iValue = (int) $oValue;

            if ($iValue > $iMaxValue) {
                $iMaxValue = $iValue;
            }
        }

        return $iMaxValue;
    }

    public function fetchArrayColumns(array $adParent, array $viColumnKeys): array
    {
        $iParentRowsNumber = count($adParent);
        $iChildColsNumber = count($viColumnKeys);

        $adChild = $this->initArray(
            'int',
            $iParentRowsNumber,
            $iChildColsNumber
        );

        for ($i = 0; $i < $iParentRowsNumber; $i++) {
            for ($j = 0; $j < $iChildColsNumber; $j++) {
                $adChild[$i][$j] = $adParent[$i][$viColumnKeys[$j]];
            }
        }

        return $adChild;
    }
    /**
     * @throws Exception
     */
    public function adMultiply(array $a, array $b, int $s = 1): array
    {
        $m1 = count($a); // n1 = m2
        $n1 = count($a[0]);
        $m2 = count($b);
        $n2 = count($b[0]);

        if ($n1 !== $m2) {
            throw new Exception("Illegal matrix dimensions.");
        }

        $c = $this->initArray('int', $m1, $n2);

        for ($i = 0; $i < $m1; $i++) {
            for ($j = 0; $j < $n2; $j++) {
                for ($k = 0; $k < $n1; $k++) {
                    $c[$i][$j] += $a[$i][$k] * $b[$k][$j] * $s;


//                    if (empty($c[$i][$j])) {
//                        $c[$i][$j] = $a[$i][$k] * $b[$k][$j] * $s;
//                    } else {
//                        dump($i, $j, $k, $c[$i][$j], $b[$k][$j]);
//
//
//
//                        $c[$i][$j] += $a[$i][$k] * $b[$k][$j] * $s;
//                    }
                }
            }
        }

        return $c;
    }

    public function initArray(string $type, int $iL, int $jL = null, int $kL = null): array
    {//type = 'string'; 'int'; 'float'; 'null'
        $arr = [];

        $val = $type === 'string' ? '' : null;
        $val = $type === 'int' ? 0 : $val;
        $val = $type === 'float' ? 0.0 : $val;

        for ($i = 0; $i < $iL; $i++) {
            if ($jL !== null) {
                for ($j = 0; $j < $jL; $j++) {
                    if ($kL !== null) {
                        for ($k = 0; $k < $kL; $k++){
                            $arr[$i][$j][$k] = $val;
                        }
                    } else {
                        $arr[$i][$j] = $val;
                    }
                }
            } else {
                $arr[$i] = $val;
            }
        }

        return $arr;
    }
}
