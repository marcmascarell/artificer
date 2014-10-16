<?php namespace Mascame\Artificer;

use App;

class Artificer  {

	public static function assets()
	{
		return BaseController::assets();
	}

    public static function getCurrentModelId($items) {
        return BaseModelController::getCurrentModelId($items);
    }

	public static function is_closure($t) {
		return is_object($t) && ($t instanceof \Closure);
	}

	public static function getPlugin($plugin) {
		return with(App::make('artificer-plugin-manager'))->make($plugin);
	}
}