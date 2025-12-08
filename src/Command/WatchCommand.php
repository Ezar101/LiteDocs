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
    name: 'watch',
    description: 'Watch for changes and rebuild documentation automatically.',
)]
final class WatchCommand extends Command
{
    private string $projectDir;

    private string $lastState = '';

    public function __construct()
    {
        $this->projectDir = getcwd();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $kernel = new Kernel($this->projectDir);
        $kernel->boot();
        $config = $kernel->getConfig();

        $docsDir = $this->projectDir . '/' . ($config['docs_dir'] ?? 'docs');
        $themesDir = $this->projectDir . '/themes';

        $io->title('👀 LiteDocs Watcher');
        $io->text("Watching for changes in: <info>$docsDir</info>");

        $this->runBuild($io, $kernel);

        $this->lastState = $this->getDirectoryState([$docsDir, $themesDir]);

        while (true) {
            sleep(1);

            $currentState = $this->getDirectoryState([$docsDir, $themesDir]);

            if ($currentState !== $this->lastState) {
                $io->section('♻️ Change detected! Rebuilding...');

                $kernel = new Kernel($this->projectDir);
                $kernel->boot();

                $this->runBuild($io, $kernel);

                $this->lastState = $currentState;
            }
        }

        return Command::SUCCESS;
    }

    private function runBuild(SymfonyStyle $io, Kernel $kernel): void
    {
        try {
            $startTime = microtime(true);
            $kernel->build();
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            $io->success("Build finished in {$duration}ms. Waiting for changes...");
        } catch (\Exception $e) {
            $io->error("Build failed: " . $e->getMessage());
        }
    }

    private function getDirectoryState(array $dirs): string
    {
        $signature = '';

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            );

            foreach ($iterator as $file) {
                $signature .= $file->getPathname() . ':' . $file->getMTime() . ';';
            }
        }

        return md5($signature);
    }
}
