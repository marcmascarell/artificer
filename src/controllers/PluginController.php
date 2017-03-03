<?php

namespace Mascame\Artificer;

use App;
use File;
use View;
use Redirect;
use Mascame\Arrayer\Arrayer;
use Mascame\Artificer\Options\AdminOption;

class PluginController extends BaseController
{
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
            $message = 'Successfully installed <b>'.$plugin.'</b>';
        } else {
            $from = 'installed';
            $to = 'uninstalled';
            $message = 'Successfully uninstalled <b>'.$plugin.'</b>';
        }

        $plugins = AdminOption::get('plugins');

        if (isset($plugins[$to])) {
            if (in_array($plugin, $plugins[$to])) {
                Notification::danger('Can not '.$operation.' '.$plugin.', maybe it is already '.$from);

                return Redirect::route('admin.page.plugins');
            }
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
            $file = App::make('artificer-plugin-manager')->plugins_config_file;

            $this->modifyFile($file, $plugins, $plugin, $from, $to);

            Notification::success($message);
        } catch (\Exception $e) {
            throw new \Exception('Failed to modify plugins config');
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

            if (! file_exists($file)) {
                throw new \Exception('No plugins file.');
            }
            File::put($file, with(new Arrayer($plugins))->getContent());
        }
    }
}
