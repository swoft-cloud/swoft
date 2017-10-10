<?php

namespace Swoft\Console\Input;

use Swoft\Console\CommandParser;

/**
 *
 *
 * @uses      Input
 * @version   2017年10月06日
 * @author    stelin <phpcrazy@126.com>
 * @copyright Copyright 2010-2016 swoft software
 * @license   PHP Version 7.x {@link http://www.php.net/license/3_0.txt}
 */
class Input implements IInput
{
    /**
     * @var
     */
    protected $handle = STDIN;

    /**
     * @var
     */
    private $pwd;

    /**
     * @var string
     */
    private $fullScript;

    /**
     * the script name
     * e.g `./bin/app` OR `bin/cli.php`
     *
     * @var string
     */
    private $script;

    /**
     * the command name(Is first argument)
     * e.g `start` OR `start`
     *
     * @var string
     */
    private $command;

    /**
     * Input args data
     *
     * @var array
     */
    private $args = [];

    /**
     * Input short-opts data
     *
     * @var array
     */
    private $sOpts = [];

    /**
     * Input long-opts data
     *
     * @var array
     */
    private $lOpts = [];

    /**
     * Input constructor.
     *
     * @param null|array $argv
     */
    public function __construct($argv = null)
    {
        if (null === $argv) {
            $argv = $_SERVER['argv'];
        }

        $this->pwd = $this->getPwd();
        $this->fullScript = implode(' ', $argv);
        $this->script = array_shift($argv);

        list($this->args, $this->sOpts, $this->lOpts) = CommandParser::parse($argv);

        $this->command = isset($this->args[0]) ? array_shift($this->args) : null;
    }

    public function read($question = null, $nl = false): string
    {
        fwrite($this->handle, $question . ($nl ? "\n" : ''));

        return trim(fgets($this->handle));
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function hasArg($name): bool
    {
        return isset($this->args[$name]);
    }

    public function getArg($name, $default = null)
    {
        return $this->get($name, $default);
    }

    public function getRequiredArg($name)
    {
        if ('' !== $this->get($name, '')) {
            return $this->args[$name];
        }

        throw new \InvalidArgumentException("The argument '{$name}' is required");
    }

    public function getSameArg(array $names, $default = null)
    {
        return $this->sameArg($names, $default);
    }

    public function sameArg(array $names, $default = null)
    {
        foreach ($names as $name) {
            if ($this->hasArg($name)) {
                return $this->get($name);
            }
        }

        return $default;
    }

    public function getOpt(string $name, $default = null)
    {
        if (isset($name{1})) {
            return $this->getLongOpt($name, $default);
        }

        return $this->getShortOpt($name, $default);
    }

    public function getOption(string $name, $default = null)
    {
        return $this->getOpt($name, $default);
    }

    public function getRequiredOpt($name)
    {
        if (null === ($val = $this->getOpt($name))) {
            throw new \InvalidArgumentException("The option '{$name}' is required");
        }

        return $val;
    }

    /**
     * check option exists
     *
     * @param $name
     *
     * @return bool
     */
    public function hasOpt(string $name): bool
    {
        return isset($this->sOpts[$name]) || isset($this->lOpts[$name]);
    }

    /**
     * get same opts value
     * eg: -h --help
     *
     * ```php
     * $input->sameOpt(['h','help']);
     * ```
     *
     * @param array $names
     * @param mixed $default
     *
     * @return bool|mixed|null
     */
    public function getSameOpt(array $names, $default = null)
    {
        return $this->sameOpt($names, $default);
    }

    public function sameOpt(array $names, $default = null)
    {
        foreach ($names as $name) {
            if ($this->hasOpt($name)) {
                return $this->getOpt($name);
            }
        }

        return $default;
    }

    public function getShortOpt($name, $default = null)
    {
        return $this->sOpts[$name] ?? $default;
    }

    public function hasSOpt(string $name): bool
    {
        return isset($this->sOpts[$name]);
    }

    public function getShortOpts(): array
    {
        return $this->sOpts;
    }

    /**
     * @return array
     */
    public function getSOpts(): array
    {
        return $this->sOpts;
    }

    public function getLongOpt($name, $default = null)
    {
        return $this->lOpts[$name] ?? $default;
    }

    public function hasLOpt(string $name): bool
    {
        return isset($this->lOpts[$name]);
    }

    public function getLongOpts(): array
    {
        return $this->lOpts;
    }

    public function getLOpts(): array
    {
        return $this->lOpts;
    }

    public function getOpts(): array
    {
        return array_merge($this->sOpts, $this->lOpts);
    }

    public function getFullScript(): string
    {
        return $this->fullScript;
    }

    public function getScript(): string
    {
        return $this->script;
    }

    public function getCommand($default = ''): string
    {
        return $this->command ?: $default;
    }

    public function getPwd(): string
    {
        if (!$this->pwd) {
            $this->pwd = getcwd();
        }

        return $this->pwd;
    }

    public function get($name, $default = null)
    {
        return $this->args[$name] ?? $default;
    }
}