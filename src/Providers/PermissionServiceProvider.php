<?php

namespace Mascame\Artificer\Providers;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Contracts\Permission as PermissionContract;

class PermissionServiceProvider extends \Spatie\Permission\PermissionServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param PermissionRegistrar $permissionLoader
     */
    public function boot(PermissionRegistrar $permissionLoader)
    {
        $permissionLoader->registerPermissions();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerModelBindings();

        $this->registerBladeExtensions();
    }

    /**
     * Bind the Permission and Role model into the IoC.
     */
    protected function registerModelBindings()
    {
        $config = config('admin.permission.models');

        $this->app->bind(PermissionContract::class, $config['permission']);
        $this->app->bind(RoleContract::class, $config['role']);
    }
}
