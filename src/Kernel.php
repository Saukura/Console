<?php

namespace Command;

/**
 * Class Command
 * @package Command
 */
class Kernel
{

    /**
     *
     */
    const END = "\n";

    /**
     * 脚本名
     * @var string
     */
    protected $application;

    /**
     * 传递给脚本的参数数目
     * @var int
     */
    protected $argc;

    /**
     * 传递给脚本的参数数组
     * @var array
     */
    protected $argv;

    /**
     * 环境变量
     * @var array
     */
    protected $env;

    /**
     * 版本
     * @var string
     */
    public $version = "命令行版本:1.0.0";

    /**
     * @var string
     */
    public $help = "命令行帮助:
    命令:php console.php [command] [args] --[option]
         -[value]  获取属性
         --[value] 设置属性";

    /**
     * 命令
     * @var array
     */
    public $command = [];

    /**
     * @var array
     */
    protected $style = [
        'black'  => "\033[40;37m %s \033[0m",
        'red'    => "\033[41;37m %s \033[0m",
        'green'  => "\033[42;37m %s \033[0m",
        'yellow' => "\033[43;37m %s \033[0m",
        'blue'   => "\033[44;37m %s \033[0m",
        'purple' => "\033[45;37m %s \033[0m",
        'azure'  => "\033[46;37m %s \033[0m",
        'white'  => "\033[47;37m %s \033[0m",
    ];

    /**
     * Command constructor.
     * @param int|null $argc 传递给脚本的参数数目
     * @param array|null $argv 传递给脚本的参数数组
     * @param array $env 环境变量数组
     */
    public function __construct(int $argc = null, array $argv = null, array $env = [])
    {
        /**
         * 传递给脚本的参数数目
         */
        $this->argc = is_null($argc) ? $_SERVER['argc'] : $argc;

        /**
         * 传递给脚本的参数数组
         */
        $argv = is_null($argv) ? $_SERVER['argv'] : $argv;

        /**
         * 脚本名
         */
        $this->application = array_shift($argv);

        /**
         * 传递给脚本的参数数组
         */
        $this->argv = $argv;

        /**
         * 环境变量
         */
        $this->env = array_merge($env, getenv());
    }

    /**
     *
     */
    final public function run()
    {
        $args = $this->argv;
        $command = array_shift($args);
        reset($args);
        if (empty($command)) {
            $output = sprintf($this->style['red'], $this->help);
        } else {
            if (array_key_exists($command, $this->command)) {
                $application = new $this->command[$command];
            } else {
                $application = $this;
            }
            if ($this->version()) {
                $output = sprintf($this->style['green'], $application->version);
            } elseif ($this->help()) {
                $output = sprintf($this->style['azure'], $application->help);
            } elseif ($property = $this->property($args)) {
                $output = property_exists($application, $property)
                    ? sprintf($this->style['azure'], $application->{$property})
                    : sprintf($this->style['red'],
                        sprintf("Error:%s->%s 不存在", get_class($application), $property)
                    );
            } else {
                try {
                    $args = $this->args($args);
                    $option = isset($args['option']) ? $args['option'] : [];
                    unset($args['option']);
                    $output = is_scalar($return = $application->{'handle'}($option, $args))
                        ? sprintf($this->style['green'], $return)
                        : sprintf($this->style['green'], print_r($return, true));
                } catch (\Throwable $throwable) {
                    $output = sprintf($this->style['red'],
                        sprintf("Error:%s", $throwable->getMessage())
                    );
                }
            }
        }
        empty($output) || $this->output($output);
    }

    /**
     * @return bool
     */
    final protected function version()
    {
        return in_array('-v', $this->argv)
            || in_array('-version', $this->argv);
    }

    /**
     * @return bool
     */
    final protected function help()
    {
        return in_array('-h', $this->argv)
            || in_array('-help', $this->argv);
    }

    /**
     * @param array $args
     * @return mixed|null
     */
    final protected function property(array $args)
    {
        foreach ($args as $arg) {
            if (substr($arg, 0, 2) != '--') {
                if (substr($arg, 0, 1) == '-') {
                    return str_replace('-', '', $arg);
                }
            }
        }
        return null;
    }

    /**
     * @param array $args
     * @return array
     */
    final public function args(array $args)
    {
        $temp = [];
        foreach ($args as $key => $arg) {
            if (substr($arg, 0, 2) == '--') {
                $arg = explode('=', str_replace('-', '', $arg));
                !empty($arg[0]) && $temp['option'][$arg[0]] = isset($arg[1]) ? $arg[1] : null;
            } else {
                $temp[] = $arg;
            }
        }
        return $temp;
    }

    /**
     * @param string $output
     */
    protected function output(string $output)
    {
        print_r(sprintf('%s%s', $output, self::END));
    }
}