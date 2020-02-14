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
use Composer\Package\RootPackageInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class Config
 */
final class Config
{
    /**
     * @var string
     */
    public const EXTRA_KEY = 'haxe';

    /**
     * @var array
     */
    private array $config = [
        // Defaults
        'compiler' => 'haxe',
        'src'      => [],
        'out'      => __DIR__ . \DIRECTORY_SEPARATOR . 'out',
        'watch'    => 60000,
        'config'   => 'build.hxml'
    ];

    /**
     * @var string
     */
    private string $root;

    /**
     * Config constructor.
     *
     * @param array $config
     * @param string $root
     */
    public function __construct(array $config, string $root)
    {
        $this->config = \array_merge($this->config, $config);
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getBuildConfigName(): string
    {
        return $this->config['config'];
    }

    /**
     * @return int
     */
    public function getWatchTime(): int
    {
        return (int)$this->config['watch'];
    }

    /**
     * @param Composer $composer
     * @return static
     */
    public static function fromComposer(Composer $composer): self
    {
        $extra = $composer->getPackage()->getExtra();

        $root = self::getSourceDirectory($composer);

        $config = \array_merge_recursive($extra[self::EXTRA_KEY] ?? [], [
            'src' => self::getAutoloadDirectories($composer->getPackage()),
        ]);

        return new static($config, $root);
    }

    /**
     * @param Composer $composer
     * @return string
     */
    private static function getSourceDirectory(Composer $composer): string
    {
        $config = $composer->getConfig()->getConfigSource();

        return \dirname($config->getName());
    }

    /**
     * @param RootPackageInterface $package
     * @return array
     */
    private static function getAutoloadDirectories(RootPackageInterface $package): array
    {
        $result = [];

        $autoload = $package->getAutoload();

        foreach ((array)($autoload['psr-4'] ?? []) as $path) {
            $result[] = $path;
        }

        foreach ((array)($autoload['psr-0'] ?? []) as $path) {
            $result[] = $path;
        }

        foreach ((array)($autoload['classmap'] ?? []) as $path) {
            $result[] = $path;
        }

        return $result;
    }

    /**
     * @param string $name
     * @return void
     */
    public function regenerate(string $name = 'build.hxml'): void
    {
        \file_put_contents($this->getPath($name), $this->rebuild());
    }

    /**
     * @param string $to
     * @return string
     */
    public function getPath(string $to = ''): string
    {
        $to = \str_replace('/', \DIRECTORY_SEPARATOR, $to);
        $to = \rtrim($to, '\\/');

        if (\realpath($to) === $to) {
            return $to;
        }

        return $this->root . \DIRECTORY_SEPARATOR . $to;
    }

    /**
     * @return string
     */
    public function rebuild(): string
    {
        $sources = [
            '-php ' . $this->getOutputDirectory(),
        ];

        foreach ($this->getSources() as $path) {
            $sources[] = '-cp ' . $path;
        }

        foreach ($this->getFiles() as $file) {
            $sources[] = $file->getSignature();
        }

        return \implode("\n", $sources);
    }

    /**
     * @return Watcher
     */
    public function getWatcher(): Watcher
    {
        return new Watcher($this);
    }

    /**
     * @return string
     */
    public function getOutputDirectory(): string
    {
        return $this->getPath($this->config['out']);
    }

    /**
     * @return array|string[]
     */
    public function getSources(): array
    {
        $map = fn(string $path): string => $this->getPath($path);

        return \array_map($map, $this->config['src'] ?? []);
    }

    /**
     * @return iterable|File[]
     */
    public function getFiles(): iterable
    {
        foreach ($this->getFinder() as $file) {
            yield new File($this->getOutputDirectory(), $file);
        }
    }

    /**
     * @return Finder|\SplFileInfo[]
     */
    public function getFinder(): Finder
    {
        return (new Finder())
            ->files()
            ->name('*.hx')
            ->in($this->getSources());
    }

    /**
     * @return Compiler
     */
    public function getCompiler(): Compiler
    {
        return new Compiler($this->config['compiler']);
    }
}
