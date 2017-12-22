# 命令行工具
```
命令：php console.php command [args] --[options]

自定义命令
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

注册命令 
class Console extends Kernel
{

    /**
     * @var array
     */
    public $command = [
        'example' => Example::class,
        ...
    ];
}

```

