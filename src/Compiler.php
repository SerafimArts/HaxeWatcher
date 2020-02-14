<?php

/**
 * This file is part of HaxeWatcher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\HaxeWatcher;

use Symfony\Component\Process\Process;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @var string
     */
    private string $cmd;

    /**
     * Compiler constructor.
     *
     * @param string $cmd
     */
    public function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    /**
     * @param array|string[] $args
     * @param \Closure|null $result
     * @return string
     */
    private function exec(array $args, \Closure $result = null): string
    {
        $process = new Process($this->cmd . ' ' . \implode(' ', $args));

        $process->run(static function (string $type, string $out) use ($result) {
            if ($type === 'err') {
                throw new \LogicException(\trim($out));
            }

            if ($result) {
                $result($out);
            }
        });

        return \trim($process->getOutput());
    }

    /**
     * @param string $file
     * @return string
     */
    public function compile(string $file): string
    {
        return $this->exec([$file]);
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->exec(['--version']);
    }
}
