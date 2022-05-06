<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Interfaces;

interface NetworkEquationInterface
{
    public function init(): void;

    public function initEquationMatrices(): void;

    public function prepAdJacobianVdHessian(): void;

    public function setEquationSides(array $adMAE): void;
}
