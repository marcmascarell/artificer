<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Form;
use Input;

class hasOne extends Relation {

	public function boot()
	{
		//$this->addWidget(new Chosen());
		$this->addAttributes(array('class' => 'chosen form-control'));
	}

	public function input()
	{
		$options = $this->fieldOptions;
		$modelName = $options['relationship']['model'];
		$model = \App::make('artificer-model');
		$modelClass = '\\' . $modelName;

		$data = $modelClass::all(array('id', $options['relationship']['show']))->toArray();

		$select = array();
		foreach ($data as $d) {
			$select[$d['id']] = $d[$options['relationship']['show']];
		}
		if (Input::has($this->name)) {
			$id = Input::get($this->name);
		} else if (isset($this->value->id)) {
			$id = $this->value->id;
		} else {
			$id = $this->value;
		}

		print Form::select($this->name, $select, $id, $this->getAttributes());

		$new_url = \URL::route('admin.create', array('slug' => $model->models[$modelName]['route']));
		$edit_url = \URL::route('admin.edit', array('slug' => $model->models[$modelName]['route'], 'id' => $id));
		?>
		<div class="text-right">
			<a href="<?= $edit_url ?>" target="_blank">
				<i class="fa fa-pencil"></i>
				Edit
			</a>
			&nbsp;&nbsp;
			<a href="<?= $new_url ?>" target="_blank">
				<i class="fa fa-plus"></i>
				New
			</a>
		</div>
	<?php
	}

	public function show($value = null)
	{
		$value = ($value) ?: $this->value;

		if ($value->count() > 1) {
			throw new \Exception('A record have more than 1 row relationed while marked as hasOne');
		}

		$show = $this->fieldOptions['relationship']['show'];

		print $value->$show;

//		$options = $this->fieldOptions;
//		$model = '\\' . $options['relationship']['model'];
//
//		$data = $model::findOrFail($value);
//
//		return $data->$options['relationship']['show'];
	}

}