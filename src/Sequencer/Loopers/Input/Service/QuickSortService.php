<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Service;

use Exception;

class QuickSortService
{
    private array $arr;

    /**
     * @throws Exception
     */
    public function sort(array $arr, int $idxS, int $idx): array
    {
        if (empty($arr) || count($arr[0]) === 0) {
            throw new Exception("Illegal array dimensions.");
        }

        $this->arr = $arr;

        $number = count($arr[0]);
        $this->quickSort(0, $number - 1, $idxS, $idx);

        return $this->arr;
    }

    private function quickSort(int $low, int $high, int $idxS, int $idx): void
    {
        $i = $low;
        $j = $high;

        $pivot = $this->arr[$idxS][$low + ($high - $low) / 2];

        while ($i <= $j) {

            while ($this->arr[$idxS][$i] < $pivot) {
                $i++;
            }

            while ($this->arr[$idxS][$j] > $pivot) {
                $j--;
            }

            if ($i <= $j) {
                $this->exchange($i, $j, $idxS, $idx);
                $i++;
                $j--;
            }
        }

        if ($low < $j) {
            $this->quickSort($low, $j, $idxS, $idx);
        }
        if ($i < $high) {
            $this->quickSort($i, $high, $idxS, $idx);
        }
    }

    private function exchange(int $i, int $j, int $idxS, int $idx): void
    {
        $tempS = $this->arr[$idxS][$i];
        $temp = $this->arr[$idx][$i];

        $this->arr[$idxS][$i] = $this->arr[$idxS][$j];
        $this->arr[$idx][$i] = $this->arr[$idx][$j];

        $this->arr[$idxS][$j] = $tempS;
        $this->arr[$idx][$j] = $temp;
    }
}
