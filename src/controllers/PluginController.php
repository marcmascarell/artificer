<?php namespace Mascame\Artificer;

use Redirect;
use View;
use App;
use Mascame\Artificer\Options\AdminOption;
use File;

class PluginController extends BaseController {


	public function plugins()
	{
		return View::make($this->getView('plugins'))
			->with('plugins', App::make('artificer-plugin-manager')->getAll());
	}

	public function installPlugin($plugin)
	{
		return $this->pluginOperation($plugin, 'install');
	}

	public function uninstallPlugin($plugin)
	{
		return $this->pluginOperation($plugin, 'uninstall');
	}

	/**
	 * @param string $operation
	 */
	public function pluginOperation($plugin, $operation)
	{
		$plugin = str_replace('__slash__', '/', $plugin);

		if ($operation == 'install') {
			$from = 'uninstalled';
			$to = 'installed';
			$message = 'Successfully installed <b>' . $plugin . '</b>';
		} else {
			$from = 'installed';
			$to = 'uninstalled';
			$message = 'Successfully uninstalled <b>' . $plugin . '</b>';
		}

		$plugins = AdminOption::get('plugins');

		if (in_array($plugin, $plugins[$to])) {
			Notification::danger('Can not ' . $operation . ' ' . $plugin . ', maybe it is already ' . $from);

			return Redirect::route('admin.page.plugins');
		}

		$this->makeOperation($plugins, $plugin, $from, $to, $message);

		return Redirect::route('admin.page.plugins');
	}

	/**
	 * @param string $from
	 * @param string $to
	 * @param string $message
	 */
	protected function makeOperation($plugins, $plugin, $from, $to, $message)
	{
		try {
			$file = app_path() . '/config/packages/mascame/artificer/plugins.php';

			$this->modifyFile($file, $plugins, $plugin, $from, $to);

			Notification::success($message);
		} catch (\Exception $e) {
			throw new \Exception("Failed to modify plugins config");
		}

		return Redirect::route('admin.page.plugins');
	}

	/**
	 * @param $file
	 * @param $plugins
	 * @param $plugin
	 * @param $to
	 * @throws \Exception
	 */
	protected function modifyFile($file, $plugins, $plugin, $from, $to)
	{
		if (($key = array_search($plugin, $plugins[$from])) !== false) {
			unset($plugins[$from][$key]);
			$plugins[$to][] = $plugin;

			$content = $this->addArrayConfigStart();
			$content .= $this->addArrayWrapper('installed', $this->addArrayValues($plugins['installed']));
			$content .= $this->addArrayWrapper('uninstalled', $this->addArrayValues($plugins['uninstalled']));
			$content .= $this->addArrayConfigEnd();

			if (!file_exists($file)) throw new \Exception('No plugins file.');

			File::put($file, $content);
		}
	}

	/**
	 * @return string
	 */
	protected function addArrayConfigStart()
	{
		$content = "<?php" . PHP_EOL . PHP_EOL;
		$content .= 'return array(' . PHP_EOL . PHP_EOL;

		return $content;
	}

	/**
	 * @param $key
	 * @return string
	 */
	protected function addArrayKeyStart($key)
	{
		return "\t" . '"' . $key . '" => array(' . PHP_EOL;
	}

	/**
	 * @param $key
	 * @param $values
	 * @return string
	 */
	protected function addArrayWrapper($key, $values)
	{
		$content = $this->addArrayKeyStart($key);
		$content .= $values;
		$content .= $this->addArrayKeyEnd();

		return $content;
	}

	/**
	 * @return string
	 */
	protected function addArrayKeyEnd()
	{
		return "\t" . '),' . PHP_EOL . PHP_EOL;
	}

	/**
	 * @param $array
	 * @return string
	 */
	protected function addArrayValues($array)
	{
		$content = '';

		foreach ($array as $value) {
			$content .= "\t\t" . '"' . $value . '",' . PHP_EOL;
		}

		return $content;
	}

	/**
	 * @return string
	 */
	protected function addArrayConfigEnd()
	{
		return ');';
	}

}