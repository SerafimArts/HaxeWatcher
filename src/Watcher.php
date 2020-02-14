<?php

/**
 * This file is part of HaxeWatcher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\HaxeWatcher;

use Symfony\Component\Finder\Finder;
use Yosymfony\ResourceWatcher\ContentHashInterface;
use Yosymfony\ResourceWatcher\Crc32ContentHash;
use Yosymfony\ResourceWatcher\ResourceCacheInterface;
use Yosymfony\ResourceWatcher\ResourceCachePhpFile;
use Yosymfony\ResourceWatcher\ResourceWatcher;
use Yosymfony\ResourceWatcher\ResourceWatcherResult;

/**
 * Class Watcher
 */
class Watcher
{
    /**
     * @var bool
     */
    private bool $init = false;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * Watcher constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param ResourceWatcherResult $result
     * @param \Closure $each
     * @return void
     */
    private function check(ResourceWatcherResult $result, \Closure $each): void
    {
        try {
            $each($result, $this->init);
        } finally {
            $this->init = true;
        }
    }

    /**
     * @return ContentHashInterface
     */
    private function getHash(): ContentHashInterface
    {
        return new Crc32ContentHash();
    }

    /**
     * @return ResourceCacheInterface
     */
    private function getCache(): ResourceCacheInterface
    {
        return new ResourceCachePhpFile($this->config->getOutputDirectory() . '/.watcher.cache.php');
    }

    /**
     * @param Finder $finder
     * @return ResourceWatcher
     */
    private function getWatcher(Finder $finder): ResourceWatcher
    {
        return new ResourceWatcher($this->getCache(), $finder, $this->getHash());
    }

    /**
     * @param \Closure $each
     * @return void
     */
    public function run(\Closure $each): void
    {
        $watcher = $this->getWatcher($this->config->getFinder());
        $watcher->initialize();

        while (true) {
            $this->check($watcher->findChanges(), $each);

            \usleep($this->config->getWatchTime());
        }
    }
}
