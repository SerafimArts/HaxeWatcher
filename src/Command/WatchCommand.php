<?php

/**
 * This file is part of HaxeWatcher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\HaxeWatcher\Command;

use Composer\Command\BaseCommand;
use Serafim\HaxeWatcher\Config;
use Serafim\HaxeWatcher\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WatchCommand
 */
final class WatchCommand extends BaseCommand
{
    /**
     * @var array
     */
    private array $timestamps = [];

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName('haxe-watch');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = Config::fromComposer($this->getComposer());

        $compiler = $config->getCompiler();

        $output->writeln('<comment>Run Watcher</comment> (' . $this->now() . ')');

        while (true) {
            if (($files = $this->watch($config)) !== []) {
                $config->regenerate($config->getBuildConfigName());

                try {
                    $result = $compiler->compile($config->getPath($config->getBuildConfigName())) ?: 'OK';

                    foreach ($files as $current) {
                        $current->publish();
                    }

                    $output->writeln('  - Compilation <info>' . $result . '</info> (' . $this->now() . ')');
                } catch (\Throwable $e) {
                    $output->writeln('<error>' . $e->getMessage() . '</error>');
                }
            }

            \usleep($config->getWatchTime());
        }

        return 0;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function now(): string
    {
        $time = new \DateTime();

        return $time->format(\DateTime::RFC3339);
    }

    /**
     * @param Config $config
     * @return array|File[]
     */
    private function watch(Config $config): array
    {
        $result = [];

        foreach ($config->getFiles() as $file) {
            if (($this->timestamps[$file->getId()] ?? null) !== $file->getMTime()) {
                $this->timestamps[$file->getId()] = $file->getMTime();

                $result[] = $file;
            }
        }

        return $result;
    }
}
