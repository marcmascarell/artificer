<?php

namespace Mascame\Artificer;

use App;

class Artificer
{
    public static function assets()
    {
        return BaseController::assets();
    }

    public static function getCurrentModelId($items)
    {
        return BaseModelController::getCurrentModelId($items);
    }

    public static function isClosure($t)
    {
        return is_object($t) && ($t instanceof \Closure);
    }

    public static function getPlugin($plugin)
    {
        return with(App::make('artificer-plugin-manager'))->make($plugin);
    }

    public static function store($filepath, $content, $overide = false)
    {
        if (! $filepath) {
            $pathinfo = pathinfo($filepath);
            $filepath = $pathinfo['dirname'];
        }

        $path = explode('/', $filepath);
        array_pop($path);
        $path = implode('/', $path);

        if (! file_exists($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        if (! file_exists($filepath) || $overide) {
            return \File::put($filepath, $content);
        }

        return false;
    }
}
