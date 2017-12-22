<?php

namespace Command;

/**
 * Class Command
 * @package Command
 */
abstract class Command
{
    /**
     * @var string
     */
    public $version = "版本号";

    /**
     * @var string
     */
    public $help = "命令帮助";

    /**
     * @param array $option
     * @param array $args
     * @return mixed
     */
    abstract public function handle(array $option, array $args);
}