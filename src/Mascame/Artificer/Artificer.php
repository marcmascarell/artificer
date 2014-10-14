<?php namespace Mascame\Artificer;

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

}