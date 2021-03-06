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
 * Class InfoCommand
 */
final class VersionCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setName('haxe:version');
        $this->setDescription('Display this Haxe Compiler version');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = Config::fromComposer($this->getComposer());

        $compiler = $config->getCompiler();

        $output->writeln('Haxe Compiler <info>' . $compiler->version() . '</info>');

        return 0;
    }
}
