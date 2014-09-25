<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Form;

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

//        dd($model::all(array('id', $options['relationship']['show']))->toArray());
		print Form::select($this->name, $select, $this->value, $this->getAttributes());

		$new_url = \URL::route('admin.create', array('slug' => $model->models[$modelName]['route']));
		?>
		<a href="<?= $new_url ?>" target="_blank">
			<i class="fa fa-plus"></i>
			New
		</a>
	<?php
	}

	public function show($value = null)
	{
		$value = ($value) ?: $this->value;

		$options = $this->fieldOptions;
		$model = '\\' . $options['relationship']['model'];

		$data = $model::findOrFail($value);

		return $data->$options['relationship']['show'];
	}

}