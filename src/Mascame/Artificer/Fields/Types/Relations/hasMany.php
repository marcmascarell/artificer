<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Form;

class hasMany extends Relation {

	public function boot()
	{
		//$this->addWidget(new Chosen());
		$this->addAttributes(array('class' => 'chosen form-control'));
	}

	public function input()
	{
		$options = $this->options;
		$model = '\\' . $options['relationship']['model'];

		$data = $model::all(array('id', $options['relationship']['show']))->toArray();

		$select = array();
		foreach ($data as $d) {
			$select[$d['id']] = $d[$options['relationship']['show']];
		}

//        dd($model::all(array('id', $options['relationship']['show']))->toArray());
		return Form::select($this->name, $select, $this->value, $this->getAttributes());
	}

	public function show($value = null)
	{
		$options = $this->options;
		$model = '\\' . $options['relationship']['model'];

		$data = $model::where('id', '=', $value)->get(array('email'));

		return $data;
	}

}