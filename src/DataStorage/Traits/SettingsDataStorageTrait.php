<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\DataStorage\Traits;

use Exception;

trait SettingsDataStorageTrait
{
    protected array $alOptimizationConfigs = [];
    protected array $alOptimizationIterations = [];
    protected array $hmOptimizationSettings = [];

    private string $sProcess = 'train';

    public function getAlLooperConfigs(int $iLooper): array
    {
        return $this->alOptimizationConfigs[$iLooper];
    }

    public function getHmOptimizationSettings(): array
    {
        return $this->hmOptimizationSettings;
    }

    public function setHmOptimizationSettings(array $oLooperSettings, string $looperMarker): void
    {
        $this->hmOptimizationSettings[$looperMarker] = $oLooperSettings;
    }
    /**
     * @throws Exception
     */
    public function setAlOptimizationConfigs(int $iLooper, array $alLooperConfigs): void
    {
        if ($iLooper + 1 <= count($this->alOptimizationConfigs)) {
            $this->alOptimizationConfigs[$iLooper] = $alLooperConfigs;
            return;
        }

        if ($iLooper === count($this->alOptimizationConfigs)) {
            $this->alOptimizationConfigs[$iLooper] = $alLooperConfigs;
        }
    }

    public function getAlOptimizationConfigs(): array
    {
        return $this->alOptimizationConfigs;
    }
    /**
     * @throws Exception
     */
    public function setAlOptimizationIterations(int $iLooper, array $alLooperIterations): void
    {
        if ($iLooper + 1 <= $this->alOptimizationIterations) {
            $this->alOptimizationIterations[$iLooper] = $alLooperIterations;
            return;
        }

        if ($iLooper === count($this->alOptimizationIterations)) {
            $this->alOptimizationIterations[$iLooper] = $alLooperIterations;
        }
    }

    public function getAlOptimizationIterations(): array
    {
        return $this->alOptimizationIterations;
    }

    public function getAlLooperIterations(int $iLooper): array
    {
        return $this->alOptimizationIterations[$iLooper];
    }

    public function getOLooperSettings(string $looperMarker): array
    {
        return $this->hmOptimizationSettings[$looperMarker];
    }

    public function getSettings(string $looperMarker): array
    {
        return $this->hmOptimizationSettings[$looperMarker];
    }

    public function getSettingsParameter(string $looperMarker, string $settingsParameter): mixed
    {
        $joSettings = $this->hmOptimizationSettings[$looperMarker];

        return $joSettings[$settingsParameter];
    }

    public function setSettingsParameter(string $looperMarker, string $settingsParameter, mixed $settingsValue): void
    {
        $this->hmOptimizationSettings[$looperMarker][$settingsParameter] = $settingsValue;
    }

    public function setProcess(string $sProcess): void
    {
        $this->sProcess = $sProcess;
    }

    public function getSprocess(): string
    {
        return $this->sProcess;
    }
}
