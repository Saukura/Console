<?php

namespace Command;

/**
 * Class Console
 * @package Command
 */
class Console extends Kernel
{

    /**
     * @var array
     */
    public $command = [
        'example' => Example::class,
    ];

}