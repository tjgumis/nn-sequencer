<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Sequencer;

use DI\Container;
use Exception;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Paneric\NNOptimizer\Network\Input\InputCollector;
use Paneric\NNOptimizer\Network\NetworkProcessor;
use Paneric\NNOptimizer\Network\NetworkResultsCollector;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Sequencer
{
    protected array $alLoopers;
    protected int $fixedLoopersNumber;

    protected array $processes = [
        'train' => 'trainNetwork',
        'test'  => 'propagateTest',
        'predict' => 'propagatePrediction'
    ];

    public function __construct(
        protected DataStorage $dataStorage,
        protected InputCollector $inputCollector,
        protected NetworkProcessor $networkProcessor,
        protected NetworkResultsCollector $networkResultsCollector,
        protected Container $container
    ) {
    }

    public function run(string $process, OutputInterface $output = null): void
    {
        $this->dataStorage->setProcess($process);

        $this->{$this->processes[$process]}($output);
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function trainNetwork(OutputInterface $output = null): void
    {
        $this->container->set(OutputInterface::class, $output);

        $this->initStaticLoopers();

        $alIterations = $this->dataStorage->getAlOptimizationIterations();

        $this->networkResultsCollector->init();

        $s00number = count($alIterations[0]);
        for ($s00 = 0; $s00 < $s00number; $s00++) {
            $i00 = 0;
            $i00number = $alIterations[0][$s00];
            while ($this->alLoopers[0]->setSettings(0, $s00, $i00, $i00number) < $i00number) {

                $i00++;

                $this->initDynamicLoopers();

                $alIterations = $this->dataStorage->getAlOptimizationIterations();

                $s01number = count($alIterations[1]);
                for ($s01 = 0; $s01 < $s01number; $s01++) {
                    $i01 = 0;
                    $i01number = $alIterations[1][$s01];
                    while ($this->alLoopers[1]->setSettings(1, $s01, $i01, $i01number) < $i01number) {

                        $i01++;

                        $s02number = count($alIterations[2]);
                        for ($s02 = 0; $s02 < $s02number; $s02++) {
                            $i02 = 0;
                            $i02number = $alIterations[2][$s02];
                            while ($this->alLoopers[2]->setSettings(2, $s02, $i02, $i02number) < $i02number) {

                                $i02++;

                                $s03number = count($alIterations[3]);
                                for ($s03 = 0; $s03 < $s03number; $s03++) {
                                    $i03 = 0;
                                    $i03number = $alIterations[3][$s03];
                                    while ($this->alLoopers[3]->setSettings(3, $s03, $i03, $i03number) < $i03number) {

                                        $i03++;

                                        $this->dataStorage->initResultsCollections();

                                        $s04number = count($alIterations[4]);
                                        for ($s04 = 0; $s04 < $s04number; $s04++) {
                                            $i04 = 0;
                                            $i04number = $alIterations[4][$s04];
                                            while ($this->alLoopers[4]->setSettings(4, $s04, $i04, $i04number) < $i04number) {

                                                $i04++;

                                                $s05number = count($alIterations[5]);
                                                for ($s05 = 0; $s05 < $s05number; $s05++) {
                                                    $i05 = 0;
                                                    $i05number = $alIterations[5][$s05];
                                                    while($this->alLoopers[5]->setSettings(5, $s05, $i05, $i05number) < $i05number){

                                                        $i05++;

                                                        $s06number = count($alIterations[6]);
                                                        for ($s06 = 0; $s06 < $s06number; $s06++) {
                                                            $i06 = 0;
                                                            $i06number = $alIterations[6][$s06];
                                                            while($this->alLoopers[6]->setSettings(6, $s06, $i06, $i06number) < $i06number){

                                                                $i06++;

                                                                $s07number = count($alIterations[7]);
                                                                for ($s07 = 0; $s07 < $s07number; $s07++) {
                                                                    $i07 = 0;
                                                                    $i07number = $alIterations[7][$s07];
                                                                    while($this->alLoopers[7]->setSettings(7, $s07, $i07, $i07number) < $i07number){

                                                                        $i07++;

                                                                        $s08number = count($alIterations[8]);
                                                                        for ($s08 = 0; $s08 < $s08number; $s08++) {
                                                                            $i08 = 0;
                                                                            $i08number = $alIterations[8][$s08];
                                                                            while($this->alLoopers[8]->setSettings(8, $s08, $i08, $i08number) < $i08number){

                                                                                $i08++;

                                                                                $s09number = count($alIterations[9]);
                                                                                for ($s09 = 0; $s09 < $s09number; $s09++) {
                                                                                    $i09 = 0;
                                                                                    $i09number = $alIterations[9][$s09];
                                                                                    while($this->alLoopers[9]->setSettings(9, $s09, $i09, $i09number) < $i09number){

                                                                                        $i09++;

                                                                                        $s10number = count($alIterations[10]);
                                                                                        for ($s10 = 0; $s10 < $s10number; $s10++) {
                                                                                            $i10 = 0;
                                                                                            $i10number = $alIterations[10][$s10];
                                                                                            while($this->alLoopers[10]->setSettings(10, $s10, $i10, $i10number) < $i10number){

                                                                                                $i10++;

                                                                                                $s11number = count($alIterations[11]);
                                                                                                for ($s11 = 0; $s11 < $s11number; $s11++) {
                                                                                                    $i11 = 0;
                                                                                                    $i11number = $alIterations[11][$s11];
                                                                                                    while($this->alLoopers[11]->setSettings(11, $s11, $i11, $i11number) < $i11number){

                                                                                                        $i11++;

                                                                                                        $s12number = count($alIterations[12]);
                                                                                                        for ($s12 = 0; $s12 < $s12number; $s12++) {
                                                                                                            $i12 = 0;
                                                                                                            $i12number = $alIterations[12][$s12];
                                                                                                            while($this->alLoopers[12]->setSettings(12, $s12, $i12, $i12number) < $i12number){

                                                                                                                $i12++;

                                                                                                                $this->triggerTraining($i12);
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        //$this->networkResultsCollector->writeResultsToFile();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //$this->networkResultsCollector->writeResultsCompactedToFile();
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function initStaticLoopers(): void
    {
        $asLoopersMarkers = $this->container->get('fixed_loopers_markers');

        $this->fixedLoopersNumber = count($asLoopersMarkers);

        for ($iLooper = 0; $iLooper < $this->fixedLoopersNumber; $iLooper++) {
            $this->alLoopers[$iLooper] = $this->container->get($asLoopersMarkers[$iLooper]);
            ($this->alLoopers[$iLooper])->init($iLooper);
        }
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function initDynamicLoopers(): void
    {
        $orderLooperSettings = ($this->alLoopers[0])->getSettingsAsJSONArray();

        $iSettings = -1;

        for ($iLooper = $this->fixedLoopersNumber; $iLooper < 13; $iLooper++) {
            $iSettings++;
            $this->alLoopers[$iLooper] = $this->container->get($orderLooperSettings[$iSettings]);
            ($this->alLoopers[$iLooper])->init($iLooper);
        }
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function triggerTraining(int $i12): void
    {
        $this->inputCollector->init();

        $this->networkProcessor->init();
        $this->networkProcessor->run();
    }
}
