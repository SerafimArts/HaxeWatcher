<?php

/**
 * This file is part of HaxeWatcher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\HaxeWatcher;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Serafim\HaxeWatcher\Command\VersionCommand;
use Serafim\HaxeWatcher\Command\WatchCommand;

/**
 * Class Plugin
 */
final class Plugin implements PluginInterface, CommandProvider, Capable
{
    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        // Do nothing
    }

    /**
     * {@inheritDoc}
     */
    public function getCommands(): array
    {
        return [
            new WatchCommand(),
            new VersionCommand(),
        ];
    }

    /**
     * @return array
     */
    public function getCapabilities(): array
    {
        return [
            CommandProvider::class => self::class,
        ];
    }
}
