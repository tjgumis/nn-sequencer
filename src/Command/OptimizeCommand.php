<?php

declare(strict_types=1);

namespace Paneric\NNOptimizer\Command;

use Paneric\NNOptimizer\Sequencer\Sequencer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class OptimizeCommand extends Command
{
    private array $processes = ['train', 'test', 'predict'];

    private const OPTION_PROCESS = 'process';
    private const OPTION_PROCESS_SHORTCUT = 'p';

    protected static $defaultName = 'nno:start';//php app nno:start or bin/app

    protected static $defaultDescription = 'Creates a new user.';

    public function __construct(protected Sequencer $optimizer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_PROCESS,
            self::OPTION_PROCESS_SHORTCUT,
            InputOption::VALUE_OPTIONAL,
            'Train network with train data'
        )->setDescription(
            '...'
        )->setHelp(
            'This command allows you to create a user...'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = $input->getOption(self::OPTION_PROCESS);

        if ($process === null || !in_array($process, $this->processes, true)) {
            $output->writeln(sprintf(
                '<error>%s</error>',
                'Missing execution process name (-p train, -p test, -p predict)',
            ));
            return Command::FAILURE;
        }

        try {
            $this->optimizer->run($process, $output);

            return Command::SUCCESS;
        } catch (Throwable $e) {
            echo sprintf(
                '%s%s%s%s%s%s%s',
                        '-------------------------------' . PHP_EOL,
                '** NN-OPTIMIZER ** Fatal error:' . PHP_EOL,
                '-------------------------------' . PHP_EOL,
                'Caught Error: ' . $e->getMessage() . PHP_EOL,
                'In file: ' . $e->getFile() . '(' . $e->getLine() . ')' . PHP_EOL,
                'Stack trace:' . PHP_EOL,
                $e->getTraceAsString() . PHP_EOL
            );
        }

        return Command::FAILURE;
    }
}
