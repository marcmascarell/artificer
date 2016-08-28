<?php namespace Mascame\Artificer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;


class InstallServiceProvider extends ServiceProvider {


	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        // Avoid installing plugins when using CLI
        if (\App::runningInConsole() || \App::runningUnitTests()) return true;

        if (! self::isInstalling() && ! self::isInstalled()) {
            $this->goToInstall();
        }
    }


    public static function isInstalled() {
        if (! \Schema::hasTable(config('admin.migrations'))) {
            return false;
        }

        $pluginManager = Artificer::pluginManager();
        $widgetManager = Artificer::widgetManager();

        foreach (Artificer::getCoreExtensions() as $coreExtension) {

            if (! $pluginManager->isInstalled($coreExtension)
                && ! $widgetManager->isInstalled($coreExtension)) {
                return false;
            }
        }

        return true;
    }

    public static function isInstalling() {
        return (Str::contains(request()->path(), 'install'));
    }

    protected function goToInstall() {
        abort(200, '', ['Location' => route('admin.install')]);
    }
}
