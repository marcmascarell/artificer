<?php namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Options\AdminOption as Option;
use Mascame\Artificer\Options\PluginOption;

abstract class Plugin {

	public $version;
	public $namespace;
	public $name;
	public $description;
	public $author;
	public $config;
	public $configKey;
	public $slug;
//	public $installed = false;
	public $options = array();
	public $routes = array();

	public function __construct($namespace)
	{
		$this->namespace = $namespace;
		$this->configKey = $this->namespace . '/' . $this->getPluginName();
		$this->config = $this->getOptions();
		$this->slug = str_replace('/', '__slash__', $this->namespace);

		$this->meta();
	}

	public function getPluginName() {
		$exploded_namespace = explode('/', $this->namespace);
		end($exploded_namespace);

		return end($exploded_namespace);
	}

	public function getPluginShowName() {
		return $this->name;
	}


	public function boot()
	{

	}

	public function meta()
	{

	}

	public function getOptions()
	{
		$this->options = PluginOption::all($this->configKey);
		return $this->options;
	}

	public function getOption($key)
	{
		return PluginOption::get($key, $this->configKey);
	}

	public function hasOption($key)
	{
		return PluginOption::has($key, $this->configKey);
	}

	public function setOption($key, $value)
	{
		PluginOption::set($key, $value, $this->configKey);

		// refresh options
		$this->getOptions();
	}

//	public function bootstrap() {
//		if (file_exists($this->path . '/routes.php')) {
//			require_once $this->path . '/routes.php';
//		}
//	}
}