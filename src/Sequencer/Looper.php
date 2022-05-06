<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer;

use Exception;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Looper
{
    public function __construct(
        protected SettingsManager $settingsManager,
        protected string $fileName,
        protected string $looperMarker
    ) {
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws JsonException
     * @throws Exception
     */
    public function init(int $iLooper): void
    {
        $alLooperConfigs = $this->settingsManager->readAlConfig($this->fileName);

        $this->settingsManager->setAlOptimizationConfigs($iLooper, $alLooperConfigs);

        $this->settingsManager->setAlOptimizationIterations($iLooper, $alLooperConfigs);
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function setSettings(int $iLooper, int $iConfig, int $iIteration, int $iIterationNumber): int
    {
        if ($iIteration < $iIterationNumber) {
            $iIteration = $this->settingsManager->setSettings($iLooper, $iConfig, $iIteration, $this->looperMarker);
        }

        if ($iIterationNumber === -1) {
            $iIteration = $this->settingsManager->setSettings($iLooper, $iConfig, $iIteration, $this->looperMarker);
        }

        return $iIteration;
    }

    public function getSettingsAsObject(): array
    {
        return $this->settingsManager->getOLooperSettings($this->looperMarker);
    }

    public function getSettingsAsJSONArray(): array
    {
        return $this->settingsManager->getOLooperSettings($this->looperMarker);
    }
}
