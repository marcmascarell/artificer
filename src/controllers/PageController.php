<?php namespace Mascame\Artificer;

use Redirect;
use Mascame\Artificer\Options\AdminOption;

class PageController extends BaseController {

	public function home()
	{
		return Redirect::to(AdminOption::get('default_route'));
	}

}