<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\DataStorage\Traits;

trait EquationLMDataStorageTrait
{
    private array $adEQL;
    private array $vdEQR;

    private array $vdWeightsDelta;
    private bool $bMatrixSingularity;

    public function getAdEQL(): array
    {
        return $this->adEQL;
    }

    public function getVdEQR(): array
    {
        return $this->vdEQR;
    }

    public function getVdWeightsDelta(): array
    {
        return $this->vdWeightsDelta;
    }

    public function getbMatrixSingularity(): bool
    {
        return $this->bMatrixSingularity;
    }

    public function setAdEQL(array $adEQL): void
    {
        $this->adEQL = $adEQL;
    }

    public function setVdEQR(array $vdEQR): void
    {
        $this->vdEQR = $vdEQR;
    }

    public function setVdWeightsDelta(array $vdWeightsDelta): void
    {
        $this->vdWeightsDelta = $vdWeightsDelta;
    }

    public function setbMatrixSingularity(bool $bMatrixSingularity): void
    {
        $this->bMatrixSingularity = $bMatrixSingularity;
    }
}
