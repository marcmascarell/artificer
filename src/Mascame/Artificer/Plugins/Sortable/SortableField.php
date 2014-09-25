<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Fields\Field;
use Form;
use App;
use DB;

class SortableField extends Field {

	/*
	 * Todo: A reorder is needed when updating, currently crashing.
	 */
	public function input()
	{
		$modelObject = App::make('artificer-model');
		$table = $modelObject->models[$modelObject::getCurrent()]['table'];

		$count = DB::table($table)->count();

		return Form::select($this->name, array_combine(range(1, $count), range(1, $count)), $this->value, $this->getAttributes());
	}
}