<?php

namespace Mascame\Artificer;

use App;
use Mascame\Hooky\Hook;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Extension\ResourceCollector;
use Mascame\Artificer\Assets\AssetsManagerInterface;
use Mascame\Artificer\Controllers\BaseModelController;

class Artificer
{
    use Themable;

    protected static $coreExtensions = [
        \Mascame\Artificer\LoginPlugin::class,
    ];

    /**
     * @return array
     */
    public static function getCoreExtensions()
    {
        return self::$coreExtensions;
    }

    public static function isCoreExtension($extension)
    {
        return in_array($extension, self::$coreExtensions);
    }

    /**
     * Returns the current user's action.
     *
     * @return null|string list, edit, create, show
     */
    public static function getCurrentAction()
    {
        switch (\Route::currentRouteName()) {
            case 'admin.model.create':
                return 'create';
            case 'admin.model.edit':
                return 'edit';
            case 'admin.model.show':
                return 'show';
            case 'admin.model.all':
            case 'admin.model.filter':
                return 'list';
            default:
                return;
        }
    }

    /**
     * @return ModelManager
     */
    public static function modelManager()
    {
        return App::make('ArtificerModelManager');
    }

    /**
     * @return \Mascame\Artificer\Plugin\Manager
     */
    public static function pluginManager()
    {
        return App::make('ArtificerPluginManager');
    }

    /**
     * @return \Mascame\Artificer\Widget\Manager
     */
    public static function widgetManager()
    {
        return App::make('ArtificerWidgetManager');
    }

    /**
     * @return AssetsManagerInterface
     */
    public static function assetManager()
    {
        return App::make('ArtificerAssetManager');
    }

    /**
     * @return Hook
     */
    public static function hook()
    {
        return App::make('ArtificerHook');
    }

    /**
     * @return ResourceCollector
     */
    public static function resourceCollector()
    {
        return App::make('ArtificerResourceCollector');
    }

    public static function getCurrentModelId($items)
    {
        return BaseModelController::getCurrentModelId($items);
    }

    public static function addMenu($options)
    {
        return config(['admin.menu' => array_merge(self::getMenu(), $options)]);
    }

    protected static function getMenu()
    {
        return config('admin.menu');
    }

    public static function getAssetsPath($file = null)
    {
        if ($file) {
            $file = '/'.$file;
        }

        return 'vendor/admin'.$file;
    }

    public static function getExtensionsAssetsPath($file = null)
    {
        if ($file) {
            $file = '/'.$file;
        }

        return self::getAssetsPath('extensions'.$file);
    }
}
