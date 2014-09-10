<?php namespace Mascame\Artificer\Plugins\Datatables;

use Mascame\Artificer\Artificer;
use View;

class DatatablesController extends Artificer {


	public function configuration()
	{
		return View::make('admin::plugins.datatables.home');
	}

}