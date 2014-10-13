<?php namespace Mascame\Artificer;

use LaravelLocalization;

class Localization  {

	public function __construct() {
		dd(LaravelLocalization::getSupportedLocales());
	}

}