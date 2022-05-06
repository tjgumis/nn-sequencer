<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Service;

class InputBinarizeService
{
    public function binarize(array $a2d): array
    {
        $iL = count($a2d);
        $jL = count($a2d[0]);

        for ($i = 0; $i < $iL; $i++) {
            for ($j = 0; $j < $jL; $j++) {

                if ($a2d[$i][$j] < 0) {
                    $a2d[$i][$j] = -0.5;
                }

                if ($a2d[$i][$j] > 0) {
                    $a2d[$i][$j] = 0.5;
                }
            }
        }

        return $a2d;
    }
}
