<?php namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Options\PluginOption;
use App;

abstract class Plugin implements PluginInterface {

	/**
	 * @var
	 */
	public $version;

	/**
	 * @var
	 */
	public $namespace;

	/**
	 * @var
	 */
	public $name;

	/**
	 * @var
	 */
	public $description;

	/**
	 * @var
	 */
	public $author;

	/**
	 * @var array|mixed
	 */
	public $config;

	/**
	 * @var string
	 */
	public $configKey;

	/**
	 * @var mixed
	 */
	public $slug;

	/**
	 * @var array
	 */
	public $routes = array();

	/**
	 * @var array
	 */
	public $options = array();

	/**
	 * @var bool
	 */
	protected $installed = false;

	/**
	 * @var PluginManager
	 */
	protected $manager;

	/**
	 * @param $namespace
	 */
	public function __construct($namespace)
	{
		$this->namespace = $namespace;
		$this->configKey = $this->namespace . '/' . $this->getPluginName();
		$this->config = $this->getOptions();
		$this->slug = str_replace('/', '__slash__', $this->namespace);

		$this->manager = App::make('artificer-plugin-manager');
		$this->installed = $this->isInstalled();

		$this->meta();
	}

	abstract public function boot();

	abstract public function meta();

	/**
	 * @return bool
	 */
	public function isInstalled() {
		return ($this->manager->isInstalled($this->namespace)) ? true : false;
	}

	/**
	 * @return mixed
	 */
	public function getPluginName() {
		$exploded_namespace = explode('/', $this->namespace);

		return end($exploded_namespace);
	}

	/**
	 * @return mixed
	 */
	public function getPluginShowName() {
		return $this->name;
	}

	/**
	 * @param $array
	 */
	public function addRoutes($array) {
		if ($this->isInstalled()) $this->routes = $array;
	}

	/**
	 * @param $route
	 * @param array $params
	 * @return null|string
	 */
	protected function route($route, $params = array()) {
		if ($this->isInstalled()) {
			return route($route, $params);
		}

		return null;
	}

	/**
	 * @return array|mixed
	 */
	public function getOptions()
	{
		$this->options = PluginOption::all($this->configKey);
		return $this->options;
	}

	/**
	 * @param string $key
	 */
	public function getOption($key)
	{
		return PluginOption::get($key, $this->configKey);
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function hasOption($key)
	{
		return PluginOption::has($key, $this->configKey);
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function setOption($key, $value)
	{
		PluginOption::set($key, $value, $this->configKey);

		// refresh options
		$this->getOptions();
	}
}