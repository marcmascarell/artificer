<?php

namespace Mascame\Artificer\Plugin;

use Mascame\Arrayer\Arrayer;
use Mascame\Artificer\Options\AdminOption;

class PluginManager
{
    /**
     * @var null
     */
    public $pluginNamespace;

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var array
     */
    protected static $added_plugins = [];

    /**
     * @var array
     */
    protected static $fields = [];

    /**
     * @var array
     */
    protected static $routes = [];

    /**
     * @var array
     */
    protected static $plugins = [];

    /**
     * @var array
     */
    public static $installed_plugins_routes = [];

    /**
     * @var string
     */
    public $plugins_config_file = '/config/packages/mascame/artificer/plugins.php';

    /**
     * @param null $pluginNamespace
     * @param null $plugins_config_file
     */
    public function __construct($pluginNamespace = null, $plugins_config_file = null)
    {
        if ($pluginNamespace) {
            $this->pluginNamespace = $pluginNamespace;

            $this->addPlugin($pluginNamespace);
        }

        $this->plugins_config_file = ($plugins_config_file) ? $plugins_config_file : app_path().$this->plugins_config_file;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        if (self::$plugins) {
            return self::$plugins;
        }

        $plugins = AdminOption::get('plugins');

        if (! isset($plugins['installed'])) {
            $plugins['installed'] = [];
        }
        if (! isset($plugins['uninstalled'])) {
            $plugins['uninstalled'] = [];
        }

        if ($this->hasModifiedPlugins($plugins)) {
            $this->generatePluginsFile($plugins);
        }

        return self::$plugins = $plugins;
    }

    /**
     * @param $plugins
     * @return bool
     */
    protected function hasModifiedPlugins($plugins)
    {
        $config_plugins = array_merge($plugins['installed'], $plugins['uninstalled']);
        $new_added = array_diff(self::$added_plugins, $config_plugins);

        return ! empty($new_added) || count($config_plugins) != count(self::$added_plugins);
    }

    /**
     * @param $plugins
     */
    protected function generatePluginsFile($plugins)
    {
        $old_plugins = $plugins;
        $plugins = [];

        foreach (self::$added_plugins as $pluginNamespace) {
            if (in_array($pluginNamespace, $old_plugins['installed'])) {
                $plugins['installed'][] = $pluginNamespace;
            } else {
                $plugins['uninstalled'][] = $pluginNamespace;
            }
        }

        $arrayer = new Arrayer($plugins);
        \File::put($this->plugins_config_file, $arrayer->getContent());
    }

    /**
     * @return mixed
     */
    public function boot()
    {
        $plugins = $this->getAll();

        if (isset($plugins['installed'])) {
            $this->bootInstalled($plugins['installed']);
        }
        if (isset($plugins['uninstalled'])) {
            $this->bootUninstalled($plugins['uninstalled']);
        }

        return self::$plugins;
    }

    /**
     * @param $plugins
     */
    protected function bootInstalled($plugins)
    {
        foreach ($plugins as $key => $pluginNamespace) {
            $this->instances[$pluginNamespace] = \App::make($pluginNamespace);
            $this->instances[$pluginNamespace]->boot();

            self::$plugins['installed'][$key] = $this->instances[$pluginNamespace];

            $this->addFields($pluginNamespace);

            self::$installed_plugins_routes[] = (isset(self::$routes[$pluginNamespace])) ? self::$routes[$pluginNamespace] : null;
        }
    }

    /**
     * @param $plugins
     */
    protected function bootUninstalled($plugins)
    {
        foreach ($plugins as $key => $pluginNamespace) {
            $this->instances[$pluginNamespace] = \App::make($pluginNamespace);
            self::$plugins['uninstalled'][$key] = $this->instances[$pluginNamespace];
        }
    }

    /**
     * @param $pluginNamespace
     */
    protected function addFields($pluginNamespace)
    {
        if (isset(self::$fields[$pluginNamespace]) && ! empty(self::$fields[$pluginNamespace])) {
            $fields = AdminOption::get('classmap');

            foreach (self::$fields[$pluginNamespace] as $field => $class) {
                $fields[$field] = $class;
            }

            AdminOption::set('classmap', $fields);
        }
    }

    /**
     * @param $plugin
     * @return null
     */
    public function make($pluginNamespace)
    {
        return ($this->isInstalled($pluginNamespace)) ? $this->getPluginInstance($pluginNamespace) : null;
    }

    /**
     * @param $pluginNamespace
     * @return mixed
     */
    protected function getPluginInstance($pluginNamespace)
    {
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
        if ($this->isInstalled($key)) {
            return self::$plugins['installed'][$key];
        }

        return self::$plugins['uninstalled'][$key];
    }

    /**
     * @return array
     */
    public function getInstalledPlugins()
    {
        return (isset(self::$plugins['installed']) && ! empty(self::$plugins['installed'])) ? self::$plugins['installed'] : [];
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
    public function addPlugin($pluginName)
    {
        if (in_array($pluginName, self::$added_plugins)) {
            return false;
        }

        self::$added_plugins[] = $pluginName;

        return true;
    }

    /**
     * @param $pluginName
     * @param $field
     */
    public function addField($fieldName, $fieldClass)
    {
        self::$fields[$this->pluginNamespace][$fieldName] = $fieldClass;
    }

    /**
     * @param $pluginName
     * @param $closure
     */
    public function addRoutes($closure)
    {
        self::$routes[$this->pluginNamespace] = $closure;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }
}
