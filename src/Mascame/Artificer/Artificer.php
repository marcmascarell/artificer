<?php namespace Mascame\Artificer;

class Artificer  {


	public static function assets()
	{
		return BaseController::assets();
	}

    public static function getCurrentModelId($items) {
        return BaseModelController::getCurrentModelId($items);
    }

}