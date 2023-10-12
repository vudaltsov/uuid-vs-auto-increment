<?php

declare(strict_types=1);

namespace VUdaltsov\UuidVsAutoIncrement;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class BenchmarkCommand extends Command
{
    /**
     * @param callable(string): Writer $writerFactory
     */
    public function __construct(
        private readonly Benchmarks $benchmarks,
        private readonly Databases $databases,
        private $writerFactory,
    ) {
        parent::__construct('bench');
    }

    protected function configure(): void
    {
        $this
            ->addOption('benchmarks', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databases', mode: InputOption::VALUE_REQUIRED)
            ->addOption('total', mode: InputOption::VALUE_REQUIRED, default: 20_000_000)
            ->addOption('step', mode: InputOption::VALUE_REQUIRED, default: 200_000)
            ->addOption('select', mode: InputOption::VALUE_REQUIRED, default: 1000);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var string */
        $benchmarkNames = $input->getOption('benchmarks');
        $benchmarkNames = $benchmarkNames ? explode(',', $benchmarkNames) : null;

        /** @var string */
        $databaseNames = $input->getOption('databases');
        $databaseNames = $databaseNames ? explode(',', $databaseNames) : null;

        $total = (int) $input->getOption('total');
        \assert($total > 0);
        $step = (int) $input->getOption('step');
        \assert($step > 0);
        $select = (int) $input->getOption('select');
        \assert($select > 0);

        foreach ($this->benchmarks->get($benchmarkNames) as $benchmarkName => $benchmark) {
            foreach ($this->databases->get($databaseNames) as $databaseName => $database) {
                $writer = ($this->writerFactory)("{$benchmarkName}_{$databaseName}");

                $io->comment("Running {$benchmarkName} benchmark on {$databaseName} database...");
                $benchmark->run(
                    database: $database,
                    total: $total,
                    step: $step,
                    select: $select,
                    writer: $writer,
                );
                $io->success('Finished! Results written to ' . realpath($writer->name()));
            }
        }

        return self::SUCCESS;
    }
}
