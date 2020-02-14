<?php

/**
 * This file is part of HaxeWatcher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\HaxeWatcher;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Class File
 */
final class File
{
    /**
     * @var SplFileInfo
     */
    private SplFileInfo $file;

    /**
     * @var string
     */
    private string $output;

    /**
     * File constructor.
     *
     * @param string $output
     * @param SplFileInfo $file
     */
    public function __construct(string $output, SplFileInfo $file)
    {
        $this->file = $file;
        $this->output = $output;
    }

    /**
     * @return SplFileInfo
     */
    public function getSplInfo(): SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return \sha1_file($this->file->getPathname());
    }

    /**
     * @return int
     */
    public function getMTime(): int
    {
        return $this->file->getMTime();
    }

    /**
     * @return string
     */
    public function getPackage(): string
    {
        \preg_match('/\s*package\s+([\w.]+);/', $this->file->getContents(), $result);

        return $result[1] ?? '';
    }

    /**
     * @return bool
     */
    public function publish(): bool
    {
        $output = \dirname($this->file->getPathname()) . '/' . $this->getClassName() . '.php';

        if (\is_file($this->getOutputFilename())) {
            \copy($this->getOutputFilename(), $output);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        $cls = \explode('.', $this->file->getFilename());

        return \reset($cls);
    }

    /**
     * @return string
     */
    public function getOutputFilename(): string
    {
        $path = \str_replace('.', \DIRECTORY_SEPARATOR, $this->getSignature());

        return $this->output . \DIRECTORY_SEPARATOR . 'lib' . \DIRECTORY_SEPARATOR . $path . '.php';
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        $signature = \array_filter([$this->getPackage(), $this->getClassName()]);

        return \implode('.', $signature);
    }
}
