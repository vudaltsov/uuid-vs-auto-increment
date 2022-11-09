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
    public function __construct(
        private readonly Benchmarks $benchmarks,
        private readonly Databases $databases,
        private readonly WriterFactory $writerFactory,
    ) {
        parent::__construct('bench');
    }

    protected function configure(): void
    {
        $this
            ->addOption('benchmarks', mode: InputOption::VALUE_REQUIRED)
            ->addOption('databases', mode: InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $benchmarkNames = $input->getOption('benchmarks');
        $benchmarkNames = $benchmarkNames ? explode(',', $benchmarkNames) : null;

        $databaseNames = $input->getOption('databases');
        $databaseNames = $databaseNames ? explode(',', $databaseNames) : null;

        foreach ($this->benchmarks->get($benchmarkNames) as $benchmarkName => $benchmark) {
            foreach ($this->databases->get($databaseNames) as $databaseName => $database) {
                $writer = $this->writerFactory->createWriter($benchmarkName, $databaseName);
                $io->comment("Running {$benchmarkName} benchmark on {$databaseName} database...");
                $benchmark->run($database, $writer);
                $io->success('Finished! Results written to '.realpath($writer->name()));
            }
        }

        return self::SUCCESS;
    }
}
