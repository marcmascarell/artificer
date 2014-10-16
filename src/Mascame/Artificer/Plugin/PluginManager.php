<?php namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\Option;

class PluginManager {

	public $plugins = null;

	public function getAll()
	{
		if ($this->plugins) return $this->plugins;

		$plugins = AdminOption::get('plugins');
		$all_plugins = array_merge($plugins['installed'], $plugins['uninstalled']);

		foreach ($all_plugins as $pluginNamespace) {
			$plugin = Option::get('plugins/' . $pluginNamespace . '/' . $this->getName($pluginNamespace));
			$pluginClass = $plugin['plugin'];

			if (in_array($pluginNamespace, $plugins['installed'])) {
				$this->plugins['installed'][$pluginNamespace] = $pluginClass;
			} else {
				$this->plugins['uninstalled'][$pluginNamespace] = $pluginClass;
			}
		}

		return $this->plugins;
	}

	public function boot()
	{
		$plugins = $this->getAll();

		foreach ($plugins['installed'] as $namespace => $pluginClass) {
			$plugins['installed'][$namespace] = new $pluginClass($namespace);
			$plugins['installed'][$namespace]->boot();
		}

		foreach ($plugins['uninstalled'] as $namespace => $pluginClass) {
			$plugins['uninstalled'][$namespace] = new $pluginClass($namespace);
		}

		return $this->plugins = $plugins;
	}

	public function make($plugin) {
		return ($this->isInstalled($plugin)) ? $this->plugins['installed'][$plugin] : null;
	}

	public function getName($pluginNamespace)
	{
		$pluginName = explode('/', $pluginNamespace);

		return end($pluginName);
	}

	public function get($key)
	{
		if ($this->isInstalled($key)) return $this->plugins['installed'][$key];

		return $this->plugins['uninstalled'][$key];
	}

	public function getInstalledPlugins() {
		return (isset($this->plugins['installed']) && !empty($this->plugins['installed'])) ? $this->plugins['installed'] : array();
	}

	public function isInstalled($key)
	{
		return (array_key_exists($key, $this->getInstalledPlugins()));
	}

}