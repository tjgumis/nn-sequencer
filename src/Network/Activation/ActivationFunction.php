<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Activation;

use Exception;

class ActivationFunction
{
    /**
     * @throws Exception
     */
    public function compute(string $name, float $p, float $v): float
    {
        if (!method_exists($this, $name)) {
            throw new Exception('Activation function ' . $name . ' does not exists.');
        }

        return $this->{$name}($p, $v);
    }

    public function fncIntegration(float $x, float $w): float
    {
        return ($w * $x);
    }

    public function fncLinear(float $a, float $swx): float
    {
        return $a * $swx;
    }

    public function drvLinear(float $a, float $swx): float
    {
        return $a;
    }

    public function drv2Linear(float $a, float $swx): float
    {
        return 0;
    }

    public function fncSigmoid(float $a, float $swx): float
    {
        return (1.0 / (1.0 + exp(-$a * $swx)));
    }

    public function drvSigmoid(float $a, float $swx): float
    {
        $y = $this->fncSigmoid($a, $swx);
        return ($a * $y * (1.0 - $y));
    }

    public function drv2Sigmoid(float $a, float $swx): float
    {
        $y = $this->fncSigmoid($a, $swx);
        $dy1 = $this->drvSigmoid($a, $swx);
        return ($a * $dy1 * (1.0 - 2 * $y));
    }

    public function fncSigmoid2(float $a, float $swx): float
    {
        return 2 * (1.0 / (1.0 + exp(-$a * $swx))) - 1;
    }

    public function drvSigmoid2(float $a, float $swx): float
    {
        $y = $this->fncSigmoid2($a, $swx);
        return 2 * ($a * $y * (1.0 - $y));
    }

    public function drv2Sigmoid2(float $a, float $swx): float
    {
        $y = $this->fncSigmoid2($a, $swx);
        $dy1 = $this->drvSigmoid2($a, $swx);
        return 2 * ($a * $dy1 * (1.0 - 2 * $y));
    }

    public function fncTanH(float $a, float $swx): float
    {
        return tanh($swx);
    }

    public function drvTanH(float $a, float $swx): float
    {
        return (1.0 / (cosh($swx) ** 2.0));
    }

    public function drv2TanH(float $a, float $swx): float
    {
        return -2 * $this->fncTanH($a, $swx) * $this->drvTanH($a, $swx);
    }
}
