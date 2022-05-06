<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network\Interfaces;

interface EquationResolverInterface
{
    public function init(): void;

    public function run(): void;

    public function getVdWeightsDelta(): array;
}
