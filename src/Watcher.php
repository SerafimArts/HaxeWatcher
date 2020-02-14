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
use Serafim\HaxeWatcher\Command\WatchCommand;

/**
 * Class Watcher
 */
final class Watcher implements PluginInterface, CommandProvider, Capable
{
    /**
     * {@inheritDoc}
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $config = Config::fromComposer($composer);

        $io->write('<comment>Detect Haxe Compiler</comment>');

        $compiler = $config->getCompiler();

        $this->check($io, '  - Version %s', static function () use ($compiler): string {
            return $compiler->version();
        });
    }

    /**
     * @param IOInterface $io
     * @param string $message
     * @param \Closure $cmd
     * @return void
     */
    private function check(IOInterface $io, string $message, \Closure $cmd): void
    {
        try {
            $io->write(\sprintf($message, ''), false);

            $result = $cmd() ?: 'OK';

            $io->overwrite(\sprintf($message, '<info>' . $result . '</info>'));
        } catch (\Throwable $e) {
            $io->overwrite(\sprintf($message, '<error> ' . $e->getMessage() . ' </error>'));

            return;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCommands(): array
    {
        return [
            new WatchCommand()
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
