<?php namespace Mascame\Artificer;

use Redirect;

class PageController extends BaseController {

	public function home()
	{
        $first_model = head($this->modelObject->schema->models);

		return Redirect::route('admin.model.all', array('slug' => $first_model['route']));
	}

}