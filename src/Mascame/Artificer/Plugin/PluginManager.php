<?php namespace Mascame\Artificer\Plugin;

use Mascame\Arrayer\Arrayer;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\Option;

class PluginManager {

	public $pluginNamespace;
	protected $instances = array();
    protected static $added_plugins = array();
    protected static $fields = array();
    protected static $routes = array();
    protected static $plugins = array();
    public static $installed_plugins_routes = array();
	public static $plugins_config_file = '/config/packages/mascame/artificer/plugins.php';

    public function __construct($pluginNamespace = null) {
        if ($pluginNamespace) {
            $this->pluginNamespace = $pluginNamespace;

            $this->addPlugin($pluginNamespace);
        }
    }
    
    /**
     * @return mixed
     */
	public function getAll()
	{
		if (self::$plugins) return self::$plugins;

		$plugins = AdminOption::get('plugins');

        if (!isset($plugins['installed'])) {
            $plugins['installed'] = array();
        }

        if (!isset($plugins['uninstalled'])) {
            $plugins['uninstalled'] = array();
        }

        $new_added = array_diff(self::$added_plugins, array_merge($plugins['installed'], $plugins['uninstalled']));

        if (!empty($new_added)) {
            foreach ($new_added as $pluginNamespace) {
                $plugins['uninstalled'][] = $pluginNamespace;
            }

            $arrayer = new Arrayer($plugins);
            \File::put(self::$plugins_config_file, $arrayer->getContent());
        }

		return self::$plugins = $plugins;
	}

    /**
     * @return mixed
     */
	public function boot()
	{
        self::$plugins_config_file = app_path() . self::$plugins_config_file;
        $plugins = $this->getAll();
        $instances = array();

        if (isset($plugins['installed'])) {
            foreach ($plugins['installed'] as $key => $namespace) {
                $instances[$namespace] = \App::make($namespace);
                $instances[$namespace]->boot();

                if (isset(self::$fields[$namespace]) && !empty(self::$fields[$namespace])) {
                    $fields = AdminOption::get('classmap');

                    foreach (self::$fields[$namespace] as $field => $class) {
                        $fields[$field] = $class;
                    }

                    AdminOption::set('classmap', $fields);
                }

                self::$installed_plugins_routes[] = self::$routes[$namespace];
            }
        }

        if (isset($plugins['uninstalled'])) {
            foreach ($plugins['uninstalled'] as $key => $namespace) {
                $instances[$namespace] = \App::make($namespace);
            }
        }

		return $this->instances = $instances;
	}

    /**
     * @param $plugin
     * @return null
     */
	public function make($pluginNamespace) {
		return ($this->isInstalled($pluginNamespace)) ? $this->getPluginInstance($pluginNamespace) : null;
	}

    protected function getPluginInstance($pluginNamespace) {
        return $this->instances[$pluginNamespace];
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
		if ($this->isInstalled($key)) return self::$plugins['installed'][$key];

		return self::$plugins['uninstalled'][$key];
	}

    /**
     * @return array
     */
	public function getInstalledPlugins() {
		return (isset(self::$plugins['installed']) && !empty(self::$plugins['installed'])) ? self::$plugins['installed'] : array();
	}

    /**
     * @param $key
     * @return bool
     */
	public function isInstalled($pluginNamespace)
	{
		return in_array($pluginNamespace, $this->getInstalledPlugins());
	}

    /**
     * @param $pluginName
     * @return bool
     */
    public function addPlugin($pluginName) {
        if (in_array($pluginName, self::$added_plugins)) return false;

        self::$added_plugins[] = $pluginName;

        return true;
    }

    /**
     * @param $pluginName
     * @param $field
     */
    public function addField($fieldName, $fieldClass) {
        self::$fields[$this->pluginNamespace][$fieldName] = $fieldClass;   
    }

    /**
     * @param $pluginName
     * @param $closure
     */
    public function addRoutes($closure) {
        self::$routes[$this->pluginNamespace] = $closure;
    }

    /**
     * @return array
     */
    public static function getRoutes() {
        return self::$routes;
    }
}