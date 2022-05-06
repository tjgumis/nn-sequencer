<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Network;

use Exception;
use Paneric\NNOptimizer\DataStorage\DataStorage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NetworkProcessor
{
    protected OutputInterface $output;

    private int $epochsNumber = 3;//for testing purpose !!!

    private float $dMSEAccepted = 0.0;

    private float $dLambda;

    public function __construct(
        protected DataStorage $dataStorage,
        protected NetworkRunner $networkRunner,
        protected NetworkResults $networkResults,
        protected ContainerInterface $container
    ) {
    }
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function init(): void {
        $this->epochsNumber = (int) $this->dataStorage->getSettingsParameter(
                'process_looper',
                'epochs_number'
        );

        $this->dMSEAccepted = (float) $this->dataStorage->getSettingsParameter(
                'process_looper',
                'mse_accepted'
        );

        $this->dLambda = (float) $this->dataStorage->getSettingsParameter(
            'weight_revision_looper',
            'lambda'
        );

        $this->networkRunner->init();

        $this->networkResults->init();

        $this->output = $this->container->get(OutputInterface::class);
    }
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $iTrainSequencesNumber = $this->dataStorage->getItrainSequencesNumber();
        $iTestSequencesNumber = $this->dataStorage->getItestSequencesNumber();
        $iPredictSequencesNumber = $this->dataStorage->getIpredictSequencesNumber();

        $this->dataStorage->setDLambda($this->dLambda);
        $this->dataStorage->setShiftedDLambda(false);

//        if ($iTrainSequencesNumber * $iTestSequencesNumber * $iPredictSequencesNumber > 0) {
//            throw new Exception('Sequences not properly established');
//        }

        $iEpoch = 0;

        $this->networkRunner->initDMSEBefore();
        $this->dataStorage->setbMatrixSingularity(false);

//dump('--------------------');
        while ($iEpoch <= $this->epochsNumber) {
            $this->networkRunner->train($iTrainSequencesNumber);
            $trainMSEBefore = $this->networkRunner->getDMSEBefore();
            $trainMSEAfter = $this->networkRunner->getDMSEAfter();

            $this->networkRunner->propagateTestData($iTestSequencesNumber);
            $testMSE = $this->networkRunner->getDMSE();

            $this->networkRunner->propagatePredictData($iPredictSequencesNumber);
            $predictMSE = $this->networkRunner->getDMSE();

            if ($trainMSEBefore <= $trainMSEAfter) {
//                $this->output->writeln(sprintf(
//                    '<fg=red;options=bold>%s %s %s</>',
//                    'FLATTEN',
//                    $trainMSEBefore,
//                    $trainMSEAfter
//                ));
                $iEpoch = $this->epochsNumber + 1;
                continue;
            }

//            if ($this->dataStorage->getbMatrixSingularity()) {
//                $this->output->writeln(sprintf(
//                    '<fg=red;options=bold>%s</>',
//                    'SINGULAR'
//                ));
//                $iEpoch = $this->epochsNumber + 1;
//                continue;
//            }

            //https://symfony.com/blog/new-in-symfony-4-1-advanced-console-output
            if ($trainMSEAfter <= $this->dMSEAccepted) {
                $this->output->writeln(sprintf(
                    '<fg=yellow;options=bold>%s %s %s</>',
                    $iEpoch,
                    $trainMSEBefore,
                    $trainMSEAfter
                ));
            }

            if ($iEpoch === $this->epochsNumber && $trainMSEAfter <= $this->dMSEAccepted) {
//                dump($this->networkResults->getA2dEpochTrainResults());

//                $this->networkResults->setCurrentSettings();
//                $this->networkResults->setCurrentAllMSE();
//                $this->networkResults->setCurrentWeights();
//
//                $this->networkResults->setForTrain();
//                $this->networkResults->setForTest();
//                $this->networkResults->setForPredict();

//                dump($this->dMSEAccepted . ' ' . $results['after']);

//                $iEpoch = $this->epochsNumber + 1;
//                continue;
            }

            $iEpoch++;
        }

        $this->dataStorage->setDLambda($this->dLambda);// dLambda reinitialisation
    }
}
