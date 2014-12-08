<?php namespace Mascame\Artificer;

use Mascame\Artificer\Options\AdminOption;
use Redirect;

class PageController extends BaseController {

	public function home()
	{
        $hidden_models = AdminOption::get('models.hidden');
        $non_hidden_models = array_diff(array_keys($this->modelObject->schema->models), $hidden_models);

        $first_model = head($non_hidden_models);

		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->schema->models[$first_model]['route']));
	}

}