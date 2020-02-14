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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yosymfony\ResourceWatcher\ResourceWatcherResult;

/**
 * Class WatchCommand
 */
final class WatchCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName('haxe:watch');
        $this->setDescription('Run Haxe watcher and compile sources if necessary');
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = Config::fromComposer($this->getComposer());

        $watcher = $config->getWatcher();

        $output->writeln($this->message('<comment>Run Watcher</comment> (%s)'));

        $watcher->run(function (ResourceWatcherResult $result, bool $initialized) use ($config, $output): void {
            $hxml = $config->getBuildConfigName();

            if ($initialized === false || $result->hasChanges()) {
                $config->regenerate($hxml);

                try {
                    $out = $config->getCompiler()
                        ->compile($config->getPath($hxml)) ?: 'OK';

                    foreach ($config->getFiles() as $file) {
                        $file->publish();
                    }

                    $output->writeln($this->message("  - Compilation <info>$out</info> (%s)"));
                } catch (\Throwable $e) {
                    $message = $e->getMessage();

                    $output->writeln($this->message("  - Compilation <error>$message</error> (%s)"));
                }
            }
        });

        return 0;
    }

    /**
     * @param string $message
     * @return string
     * @throws \Exception
     */
    private function message(string $message): string
    {
        return \sprintf($message, (new \DateTime())->format(\DateTime::RFC3339));
    }
}
