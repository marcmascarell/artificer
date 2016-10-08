<?php

namespace Mascame\Artificer\Extension;

use Mascame\Extender\Installer\AbstractInstaller;
use Mascame\Extender\Installer\InstallerInterface;

class DatabaseInstaller extends AbstractInstaller implements InstallerInterface
{
    protected $repository;

    protected $type;

    protected static $booted = false;

    protected static $extensionsByType = [
        'plugins' => [
            'installed' => [],
            'uninstalled' => [],
        ],
        'widgets' => [
            'installed' => [],
            'uninstalled' => [],
        ],
    ];

    protected $extensions = [];

    protected $connection = null;

    protected $table = null;

    public function __construct($type)
    {
        $this->connection = config('admin.extension_drivers.database.connection');
        $this->table = config('admin.extension_drivers.database.table');

        if (! self::$booted && \Schema::connection($this->connection)->hasTable($this->table)) {
            $this->boot();
        }

        $this->type = $type;
        $this->extensions = self::$extensionsByType[$type];
    }

    protected function table()
    {
        //        return
    }

    protected function boot()
    {
        self::$booted = true;

        return $this->model()->all()->groupBy('type')->map(function ($typeItems) {
            return $typeItems->groupBy('status')->map(function ($items) {
                return $items->pluck('name');
            });
        })->each(function ($items, $key) {
            self::$extensionsByType[$key] = array_merge(self::$extensionsByType[$key], $items->toArray());
        });
    }

    protected function model()
    {
        return \Mascame\Artificer\Model\FakeModel::make('ArtificerExtension', [
            'connection' => $this->connection,
            'table' => $this->table,
        ]);
    }

    public function handleExtensionChanges($extensions)
    {
        if (empty($extensions)) {
            return;
        }

        $storedExtensions = array_merge($this->extensions[self::STATUS_INSTALLED], $this->extensions[self::STATUS_UNINSTALLED]);

        $added = array_diff($extensions, $storedExtensions);
        $removed = array_diff($storedExtensions, $extensions);

        if (! empty($added)) {
            $this->addExtensions($added);
        }
        if (! empty($removed)) {
            $this->removeExtensions($removed);
        }
    }

    protected function removeExtensions($extensions)
    {
        foreach ($extensions as $extension) {
            $this->model()->where('name', $extension)->delete();
        }
    }

    protected function addExtensions($extensions)
    {
        foreach ($extensions as $extension) {
            $this->model()->create(
                [
                    'name' => $extension,
                    'status' => self::STATUS_UNINSTALLED,
                    'type' => $this->type,
                ]
            );
        }
    }

    public function getInstalled()
    {
        return $this->extensions[self::STATUS_INSTALLED];
    }

    public function getUninstalled()
    {
        return $this->extensions[self::STATUS_UNINSTALLED];
    }

    public function isInstalled($name)
    {
        return in_array($name, $this->extensions[self::STATUS_INSTALLED]);
    }

    /**
     * @param $extension
     * @return bool
     * @throws \Exception
     */
    public function install($extension)
    {
        return $this->action($extension, self::ACTION_INSTALL);
    }

    /**
     * @param $extension
     * @return bool
     * @throws \Exception
     */
    public function uninstall($extension)
    {
        return $this->action($extension, self::ACTION_UNINSTALL);
    }

    /**
     * @param $extension
     * @param $action
     * @return bool
     * @throws \Exception
     */
    protected function action($extension, $action)
    {
        if ($this->hasDispatcher()) {
            $this->fire("before.{$action}.{$extension}", [$extension]);
        }

        $result = $this->makeOperation($extension, $action);

        if ($this->hasDispatcher()) {
            $this->fire("after.{$action}.{$extension}", [$extension]);
        }

        return $result;
    }

    protected function makeOperation($extension, $action)
    {
        return $this->model()->updateOrCreate(
            [
                'name' => $extension,
            ],
            [
                'name' => $extension,
                'status' => $this->actionToStatus[$action],
                'type' => $this->type,
            ]
        );
    }
}
