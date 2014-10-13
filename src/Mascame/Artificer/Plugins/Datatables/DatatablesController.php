<?php namespace Mascame\Artificer\Plugins\Datatables;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\BaseModelController;
use View;

class DatatablesController extends BaseModelController {


	public function configuration()
	{
		return View::make('admin::plugins.datatables.home');
	}

}