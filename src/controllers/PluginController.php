<?php namespace Mascame\Artificer;

use Redirect;
use View;
use App;
use Mascame\Artificer\Options\AdminOption;
use File;

class PluginController extends BaseController {

	public function home()
	{
		return Redirect::to(AdminOption::get('default_route'));
	}

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

		try {
			if (($key = array_search($plugin, $plugins[$from])) !== false) {
				unset($plugins[$from][$key]);
				$plugins[$to][] = $plugin;

				$content = "<?php" . PHP_EOL . PHP_EOL;
//				$content .= 'return ' . var_export($plugins, true);
//				$content .= ';';
				$content .= 'return array(' . PHP_EOL . PHP_EOL;

				$content .= "\t" . '"installed" => array(' . PHP_EOL;
				foreach ($plugins['installed'] as $plugin) {
					$content .= "\t\t" . '"' . $plugin . '",' . PHP_EOL;
				}
				$content .= "\t" . '),' . PHP_EOL . PHP_EOL;

				$content .= "\t" . '"uninstalled" => array(' . PHP_EOL;
				foreach ($plugins['uninstalled'] as $plugin) {
					$content .= "\t\t" . '"' . $plugin . '",' . PHP_EOL;
				}
				$content .= "\t" . '),' . PHP_EOL . PHP_EOL;

				$content .= ');';

				$file = app_path() . '/config/packages/mascame/artificer/plugins.php';
				if (file_exists($file)) {
					File::put($file, $content);
				} else {
					throw new \Exception('No plugins file.');
				}
			}

			Notification::success($message);
		} catch (\Exception $e) {
			throw new \Exception("Failed to modify plugins config");
		}

		return Redirect::route('admin.page.plugins');

	}

}