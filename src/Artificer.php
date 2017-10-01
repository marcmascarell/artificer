<?php

namespace Mascame\Artificer;

use App;
use Auth;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Extension\ResourceCollector;
use Mascame\Artificer\Assets\AssetsManagerInterface;

class Artificer
{
    use Themable;

    const ACTION_BROWSE = 'browse';
    const ACTION_READ = 'read';
    const ACTION_EDIT = 'edit';
    const ACTION_ADD = 'add';
    const ACTION_DELETE = 'delete';

    /**
     * @var array
     */
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

    /**
     * @param $extension
     * @return bool
     */
    public static function isCoreExtension($extension)
    {
        return in_array($extension, self::$coreExtensions);
    }

    /**
     * Returns the current user's action.
     *
     * @return null|string browse, read, edit, add or delete
     */
    public static function getCurrentAction()
    {
        switch (\Route::currentRouteName()) {
            case 'admin.model.all':
            case 'admin.model.filter':
                return self::ACTION_BROWSE;
            case 'admin.model.show':
                return self::ACTION_READ;
            case 'admin.model.edit':
                return self::ACTION_EDIT;
            case 'admin.model.create':
                return self::ACTION_ADD;
            case 'admin.model.destroy':
                return self::ACTION_DELETE;
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
     * @return ResourceCollector
     */
    public static function resourceCollector()
    {
        return App::make('ArtificerResourceCollector');
    }

    /**
     * @param $options
     * @return mixed
     */
    public static function addMenu($options)
    {
        return config(['admin.menu' => array_merge(self::getMenu(), $options)]);
    }

    /**
     * @return mixed
     */
    protected static function getMenu()
    {
        return config('admin.menu');
    }

    /**
     * @param null $file
     * @return string
     */
    public static function getAssetsPath($file = null)
    {
        if ($file) {
            $file = '/'.$file;
        }

        return 'vendor/admin'.$file;
    }

    /**
     * @param null $file
     * @return string
     */
    public static function getExtensionsAssetsPath($file = null)
    {
        if ($file) {
            $file = '/'.$file;
        }

        return self::getAssetsPath('extensions'.$file);
    }

    public static function auth()
    {
        return Auth::guard('admin');
    }
}
