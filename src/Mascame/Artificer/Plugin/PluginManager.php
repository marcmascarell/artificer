<?php namespace Mascame\Artificer\Plugin;

use Mascame\Arrayer\Arrayer;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\Option;

class PluginManager {

	public $plugins = null;
	protected static $added_plugins = array();
	protected static $routes = array();
    public static $installed_plugins_routes = array();
	public static $plugins_config_file = '/config/packages/mascame/artificer/plugins.php';

    /**
     * @return mixed
     */
	public function getAll()
	{
		if ($this->plugins) return $this->plugins;

		$plugins = AdminOption::get('plugins');

        $new_added = array_diff(self::$added_plugins, array_merge($plugins['installed'], $plugins['uninstalled']));

        if (!empty($new_added)) {
            foreach ($new_added as $pluginNamespace) {
                $plugins['uninstalled'][] = $pluginNamespace;
            }

            $arrayer = new Arrayer($plugins);
            \File::put(self::$plugins_config_file, $arrayer->getContent());
        }

		return $this->plugins = $plugins;
	}

    /**
     * @return mixed
     */
	public function boot()
	{
        self::$plugins_config_file = app_path() . self::$plugins_config_file;
        $plugins = $this->getAll();

        if (isset($plugins['installed'])) {
            foreach ($plugins['installed'] as $key => $namespace) {
                $plugins['installed'][$key] = \App::make($namespace);
                $plugins['installed'][$key]->boot();

                self::$installed_plugins_routes[] = self::$routes[$namespace];
            }
        }

        if (isset($plugins['uninstalled'])) {
            foreach ($plugins['uninstalled'] as $key => $namespace) {
                $plugins['uninstalled'][$key] = \App::make($namespace);
            }
        }

		return $this->plugins = $plugins;
	}

    /**
     * @param $plugin
     * @return null
     */
	public function make($plugin) {
		return ($this->isInstalled($plugin)) ? $this->plugins['installed'][$plugin] : null;
	}

    /**
     * @param $pluginNamespace
     * @return mixed
     */
	public function getName($pluginNamespace)
	{
		$pluginName = explode('/', $pluginNamespace);

		return end($pluginName);
	}

    /**
     * @param $key
     * @return mixed
     */
	public function get($key)
	{
		if ($this->isInstalled($key)) return $this->plugins['installed'][$key];

		return $this->plugins['uninstalled'][$key];
	}

    /**
     * @return array
     */
	public function getInstalledPlugins() {
		return (isset($this->plugins['installed']) && !empty($this->plugins['installed'])) ? $this->plugins['installed'] : array();
	}

    /**
     * @param $key
     * @return bool
     */
	public function isInstalled($key)
	{
		return (array_key_exists($key, $this->getInstalledPlugins()));
	}

    /**
     * @param $pluginName
     * @return bool
     */
    public static function addPlugin($pluginName) {
        if (in_array($pluginName, self::$added_plugins)) return false;

        self::$added_plugins[] = $pluginName;

        return true;
    }

    /**
     * @param $pluginName
     * @param $closure
     */
    public static function addRoutes($pluginName, $closure) {
        self::$routes[$pluginName] = $closure;
    }

    /**
     * @return array
     */
    public static function getRoutes() {
        return self::$routes;
    }
}