<?php

namespace Mascame\Artificer\Providers;

use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;
use Illuminate\Support\ServiceProvider;
use Mascame\Artificer\Middleware\InstalledMiddleware;

class InstallServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        \App::make('router')->middleware('artificer-installed', InstalledMiddleware::class);

        // Avoid redirection when using CLI
        if (\App::runningInConsole() || \App::runningUnitTests()) {
            return true;
        }

        if (! self::isInstalling() && ! self::isInstalled()) {
            $this->goToInstall();
        }
    }

    public static function isInstalled()
    {
        if (! self::isExtensionDriverReady()) {
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

    public static function isExtensionDriverReady()
    {
        $driver = config('admin.extension_driver');
        $connectionName = config('admin.extension_drivers.database.connection');
        $migrationsTable = config('admin.migrations');

        return $driver == 'file' || $driver == 'database' && \Schema::connection($connectionName)->hasTable($migrationsTable);
    }

    public static function isInstalling()
    {
        return Str::contains(request()->path(), 'install');
    }

    protected function goToInstall()
    {
        abort(200, '', ['Location' => route('admin.install')]);
    }
}
