<?php namespace Mascame\Artificer\Controllers;

use Mascame\Artificer\Artificer;

class InstallController extends BaseController
{

    protected $process = [
        'setupExtensions',
        'installCoreExtensions'
    ];

    public function home()
    {
        Artificer::assetManager()->add(['bootstrap-cdn']);

        return view('artificer::install', [
            'steps' => [
                [
                    'title' => 'Prepare extensions',
                    'icon' => 'fa fa-plug',
                    'actions' => [
                        'Will create the given migrations table<br>
                        <span class="label label-default">'. config('admin.migrations') .'</span><small> @ config(\'admin.migrations\')</small>',
                        'Will setup the given extension driver<br>
                        <span class="label label-default">'. config('admin.extension_driver') .'</span><small> @ config(\'admin.extension_driver\')</small>',
                    ]
                ],
                [
                    'title' => 'Install core extensions',
                    'icon' => 'fa fa-download',
                    'actions' => [
                        'Will install the core extensions needed to work<br>' . join('', array_map(function($extension) {
                            return '<span class="label label-default">'. $extension .'</span>';
                        }, Artificer::getCoreExtensions())),

                    ]
                ],
                // Todo
//                [
//                    'title' => 'Locate models',
//                    'icon' => 'fa fa-compass'
//                ],
                [
                    'title' => 'Create access',
                    'icon' => 'fa fa-user-plus',
                    'actions' => [
                        'Will create an admin account<br>
                        <small>Username</small>: <span class="label label-default">artificer</span> <small>Password</small>: <span class="label label-default">artificer</span>'
                    ]
                ],
            ]
        ]);
    }

    public function install()
    {
        foreach ($this->process as $process) {
            $this->$process();
        }

        return ['installed' => true];
    }

    /**
     * This will create the 'artificer_extensions' if does not exist.
     *
     * In case that 'artificer_migrations' does not exist it will also be created automatically.
     *
     * @return bool
     */
    protected function setupExtensions() {
        if (config('admin.extension_driver') == 'database' && ! \Schema::hasTable('artificer_extensions')) {
            $path = __DIR__ . '/../../migrations/';

            if (str_contains($path, base_path() . '/')) {
                $path = str_replace(base_path() . '/', '', $path);
            }

            $migrator = app('ArtificerMigrator');
            $migrator->path($path);

            \Artisan::call('artificer:migrate', ['--path' => $path]);
        }

        return true;
    }

    protected function installCoreExtensions() {
        $pluginManager = Artificer::pluginManager();
        $widgetManager = Artificer::widgetManager();

        // Enable install events
        Artificer::pluginManager()->boot();
        Artificer::widgetManager()->boot();

        foreach (Artificer::getCoreExtensions() as $coreExtension) {

            if (! $pluginManager->isInstalled($coreExtension)
                && ! $widgetManager->isInstalled($coreExtension)) {

                // Todo: know if its plugin or widget
                $installed = $pluginManager->installer()->install($coreExtension);

                if (! $installed) {
                    throw new \Exception("Unable to install Artificer core extension {$coreExtension}");
                }
            }
        }

    }

}