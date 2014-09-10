<?php namespace Mascame\Artificer;

use Config;
use Str;
use Mascame\Artificer\Options\AdminOption as Option;

class Plugin extends Artificer {

	public $version;
	public $namespace;
	public $name;
	public $description;
	public $author;
	public $config;
	public $configKey;
	public $path;
	public $slug;
	public $options = array();

	public function __construct($namespace, $path) {
		$this->path = $path;
		$this->namespace = $namespace;
		$this->configKey = 'plugins/' . $namespace;
		$this->config = (Option::has($this->configKey)) ? Option::get($this->configKey) : null;
		$this->slug = str_replace('/', '-', $this->namespace);
//		$this->bootstrap();
	}

	public static function install()
	{

	}

//	public function bootstrap() {
//		if (file_exists($this->path . '/routes.php')) {
//			require_once $this->path . '/routes.php';
//		}
//	}
}