<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer\Loopers\Input\Service;

use RuntimeException;

class InputGradatorService
{
    public function gradate(array $a2d, int $iGradationCoefficient): array
    {
        if ($iGradationCoefficient < 1) {
            throw new RuntimeException("Gradation coefficient must be integer and bigger than 0 !!!");
        }

        $iL = count($a2d);
        $jL = count($a2d[0]);

        $dGradationUnit = 1.0 / $iGradationCoefficient / 2;

        for ($i = 0; $i < $iL; $i++) {
            for ($j = 0; $j < $jL; $j++) {
                $iGrade = (int) ($a2d[$i][$j] / $dGradationUnit);

                if(($iGrade % 2) === 0) {
                    $iGrade++;
                }

                $a2d[$i][$j] = $iGrade * $dGradationUnit;
            }
        }

        return $a2d;
    }
}
