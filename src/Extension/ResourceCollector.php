<?php namespace Mascame\Artificer\Extension;

class ResourceCollector extends \Illuminate\Support\ServiceProvider {

    protected $class = null;
    protected static $collected = [];

    public function __construct(\Illuminate\Contracts\Foundation\Application $app, $class)
    {
        parent::__construct($app);
        $this->class = $class;
    }


    public function mergeConfigFrom($path, $key)
    {
        return $this->collect('mergeConfigFrom', func_get_args());
    }

    public function mergeRecursiveConfigFrom($path, $key)
    {
        return $this->collect('mergeRecursiveConfigFrom', func_get_args());
    }

    /**
     * @param string $path
     * @param string $namespace
     */
    public function loadViewsFrom($path, $namespace)
    {
        return $this->collect('loadViewsFrom', func_get_args());
    }

    /**
     * @param string $path
     * @param string $namespace
     */
    public function loadTranslationsFrom($path, $namespace)
    {
        return $this->collect('loadTranslationsFrom', func_get_args());
    }

    /**
     * @param array|string $paths
     */
    public function loadMigrationsFrom($paths)
    {
        return $this->collect('loadMigrationsFrom', func_get_args());
    }

    /**
     * @param array $paths
     * @param null $group
     */
    public function publishes(array $paths, $group = null)
    {
        return $this->collect('publishes', func_get_args());
    }

    /**
     * @param array|mixed $commands
     */
    public function commands($commands)
    {
        return $this->collect('commands', func_get_args());
    }

    /**
     * @param $method
     * @param $args
     */
    protected function collect($method, $args) {
        self::$collected[$this->class][] = [
            'method' => $method,
            'args' => $args
        ];
    }

    /**
     * @return array
     */
    public function getCollected() {
        return self::$collected;
    }

}