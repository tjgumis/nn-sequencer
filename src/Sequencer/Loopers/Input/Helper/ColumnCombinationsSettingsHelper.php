<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Helper;

use Exception;
use JetBrains\PhpStorm\Pure;

class ColumnCombinationsSettingsHelper
{
    private int $iK = 0;
    private int $iCombination;

    #[Pure]
    public function setCombinationsNumber(int $n, int $k): int
    {
        if($n >= $k) {
            return $this->getFact($n) / ($this->getFact($n-$k) * $this->getFact($k));
        }

        return 0;
    }

    private function getFact(int $n): int
    {
        $f=1;

        for($i = $n; $i >= 1; $i--) {
            $f*=$i;
        }

        return $f;
    }
    /**
     * @throws Exception
     */
    public function setCombinationParameters(int $iIteration, array $viNumberOfCombinationTypes): void
    {
        $iL = count($viNumberOfCombinationTypes);

        for ($i = 0; $i < $iL - 1; $i++) {
            if ($viNumberOfCombinationTypes[$i] <= $iIteration && $iIteration < $viNumberOfCombinationTypes[$i + 1]) {
                $this->iK = $i + 1;

                $this->iCombination = $viNumberOfCombinationTypes[$i];

                return;
            }
        }

        throw new Exception('Iteration exception');
    }
    /**
     * @throws Exception
     */
    public function setCombination(int $n, int $k, int $iIteration, int $iCombination): array
    {
        $combination = [];

        for ($i = 0; $i < $k; $i++) {
            $combination[$i] = $i;
        }

        if ($iIteration === $iCombination) {
            return $combination;
        }

        $iCombination++;

        while ($combination[$k - 1] < $n) {
            $t = $k - 1;
            while ($t !== 0 && $combination[$t] === $n - $k + $t) {
                $t--;
            }

            $combination[$t]++;
            for ($i = $t + 1; $i < $k; $i++) {
                $combination[$i] = $combination[$i - 1] + 1;
            }

            if ($iIteration === $iCombination) {
                return $combination;
            }

            $iCombination++;
        }

        throw new Exception('Combination exception');
    }

    public function getICombination(): int
    {
        return $this->iCombination;
    }

    public function getIK(): int
    {
        return $this->iK;
    }
}
