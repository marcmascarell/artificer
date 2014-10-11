<?php namespace Mascame\Artificer;

use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\Option;

class PluginManager {

    public $plugins = null;

    public function getAll()
    {
        return ($this->plugins) ? $this->plugins : null;
    }

    public function boot()
    {
        $plugins = AdminOption::get('plugins');
        $all_plugins = array_merge($plugins['installed'], $plugins['uninstalled']);

        foreach ($all_plugins as $pluginNamespace) {
            $plugin = Option::get('plugins/' . $pluginNamespace . '/' . $this->getName($pluginNamespace));
            $plugin = $plugin['plugin'];

            if (in_array($pluginNamespace, $plugins['installed'])) {
                $this->plugins['installed'][$pluginNamespace] = new $plugin($pluginNamespace);
                $this->plugins['installed'][$pluginNamespace]->boot();
            } else {
                $this->plugins['uninstalled'][$pluginNamespace] = new $plugin($pluginNamespace);
            }
        }

        return $this->plugins;
    }

    public function getName($pluginNamespace) {
        $pluginName = explode('/', $pluginNamespace);

        return end($pluginName);
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->plugins['installed'])) {
            return $this->plugins['installed'][$key];
        }

        return $this->plugins['uninstalled'][$key];
    }

}