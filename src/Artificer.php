<?php namespace Mascame\Artificer;

use \App;
use Mascame\Artificer\Controllers\BaseModelController;
use Mascame\Artificer\Model\ModelManager;

class Artificer
{

    protected static $coreExtensions = [
        'mascame/login'
    ];

    public static function isCoreExtension($extension) {
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
                return null;
        }
    }

    /**
     * @return ModelManager
     */
    public static function getModelManager()
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
     * @return \Stolz\Assets\Manager
     */
    public static function assetManager()
    {
        return App::make('ArtificerAssetManager');
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

    // Todo is it used anywhere?
//    public static function store($filepath = null, $content, $overide = false)
//    {
//        if (!$filepath) {
//            $pathinfo = pathinfo($filepath);
//            $filepath = $pathinfo['dirname'];
//        }
//
//        $path = explode('/', $filepath);
//        array_pop($path);
//        $path = join('/', $path);
//
//        if (!file_exists($path)) {
//            \File::makeDirectory($path, 0777, true, true);
//        }
//
//        if (!file_exists($filepath) || $overide) {
//            return \File::put($filepath, $content);
//        }
//
//        return false;
//    }
}