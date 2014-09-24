<?php namespace Mascame\Artificer\Widgets;

class Widget {

	public $name;
	public $package_assets = '/packages/mascame/artificer';

	public function __construct()
	{
		$this->name = get_called_class();

		return $this;
	}

	public function output()
	{
		return false;
	}

}