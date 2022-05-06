<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer;

use Exception;
use JsonException;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SettingsManager
{
    public function __construct(
        protected ContainerInterface $container,
        protected DataStorage $dataStorage
    ) {
    }
    /**
     * @throws JsonException
     */
    public function readAlConfig(string $fileName): array
    {
        return json_decode(
            file_get_contents($fileName),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
    /**
     * @throws Exception
     */
    public function setAlOptimizationConfigs(int $iLooper, array $alLooperConfigs): void
    {
        $this->dataStorage->setAlOptimizationConfigs($iLooper, $alLooperConfigs);
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function setAlOptimizationIterations(int $iLooper, array $alLooperConfigs): void
    {
        $alLooperIterations = [];

        $iConfig = -1;

        foreach ($alLooperConfigs as $joLooperConfig) {

            $settingsMarker = $joLooperConfig['settings_marker'];

            $settings = $this->container->get($settingsMarker);

            $iConfig++;

            $alLooperIterations[$iConfig] = $settings->getSettingsIterationsNumber($joLooperConfig);
        }

        $this->dataStorage->setAlOptimizationIterations($iLooper, $alLooperIterations);
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setSettings(int $iLooper, int $iConfig, int $iIteration, string $looperMarker): int
    {
        $alLooperConfigs = $this->dataStorage->getAlLooperConfigs($iLooper);

        $joLooperConfig = $alLooperConfigs[$iConfig];

        $settingsMarker = $joLooperConfig['settings_marker'];

        $settings = $this->container->get($settingsMarker);

        $hmOptimizationSettings = $this->dataStorage->getHmOptimizationSettings();

        $oLooperSettings = $settings->setOLooperSettings($iIteration, $joLooperConfig, $hmOptimizationSettings);

        if($oLooperSettings !== null) {
            $this->dataStorage->setHmOptimizationSettings($oLooperSettings, $looperMarker);
        }

        return $settings->getiIteration();
    }

    public function getOLooperSettings(string $looperMarker): array
    {
        return $this->dataStorage->getOLooperSettings($looperMarker);
    }
}
