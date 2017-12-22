<?php

namespace Command;

/**
 * Class Copy
 * @package Command
 */
class Example extends Command
{

    /**
     * @var string
     */
    public $version = "版本:1.0.0";

    /**
     * @var string
     */
    public $help = "帮助:
    命令:php console.php example [args] --[option]";


    public function handle(array $option, array $args)
    {
        print_r($option);
        print_r($args);
        return 'OK';
    }
}