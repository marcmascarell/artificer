<?php namespace Mascame\Artificer;

use Redirect;
use Mascame\Artificer\Options\AdminOption;

class PageController extends BaseController {

	public function home()
	{
        $first_model = head($this->modelObject->models);

		return Redirect::route('admin.all', array('slug' => $first_model['route']));
	}

}