<?php

declare(strict_types=1);

namespace LiteDocs\Command;

use LiteDocs\Core\Kernel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'build',
    description: 'Build the documentation as a static HTML site.',
)]
class BuildCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $projectDir = getcwd(); // User's current directory

        try {
            $kernel = new Kernel($projectDir);
            $kernel->boot();

            $io->title('LiteDocs Builder');
            $io->text('Configuration loaded.');

            $kernel->build();

            $io->success('Build complete!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
