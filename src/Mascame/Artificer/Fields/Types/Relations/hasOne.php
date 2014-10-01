<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Form;
use Input;
use URL;

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
        $show = $options['relationship']['show'];

        $show_query = (is_array($show)) ? array('*') : array('id', $show);
		$data = $modelClass::all($show_query)->toArray();

		$select = array();
		foreach ($data as $d) {

            if (is_array($show)) {
                $value = '';
                foreach ($show as $show_key) {
                    $value .= \Str::title($show_key) . ': ' . $d[$show_key];
                    if (end($show) != $show_key) {
                        $value .= ' | ';
                    }
                }
            } else {
                $value = $d[$show];
            }

			$select[$d['id']] = $value;
		}

		if (Input::has($this->name)) {
			$id = Input::get($this->name);
		} else if (isset($this->value->id)) {
			$id = $this->value->id;
		} else {
			$id = $this->value;
		}

		print Form::select($this->name, array('0' => '(none)') + $select, $id, $this->getAttributes());

		$new_url = \URL::route('admin.create', array('slug' => $model->models[$modelName]['route']));
		$edit_url = \URL::route('admin.edit', array('slug' => $model->models[$modelName]['route'], 'id' => $id));
		?>

		<br>
		<div class="text-right">
			<div class="btn-group">
				<a href="<?=$edit_url?>" target="_blank" type="button" class="btn btn-default">
					<i class="glyphicon glyphicon-edit"></i>
				</a>

				<a href="<?=$new_url?>" target="_blank" type="button" class="btn btn-default">
					<i class="glyphicon glyphicon-plus"></i>
				</a>
			</div>
		</div>
	<?php
	}

	public function show($value = null)
	{
		$value = ($value) ?: $this->value;

		if (!$value) return "<em>(none)</em>";

        $options = $this->fieldOptions;
        $show = $options['relationship']['show'];

        if (!is_object($value)) {
            $model = '\\' . $options['relationship']['model'];

            $data = $model::findOrFail($value);

            if (is_array($show)) {
                foreach ($show as $item) {
                    print $data->$item . "<br>";
                }
                return null;
            } else {
                return $data->$show;
            }
        }

		if (!$value) {
			throw new \Exception('The (hasOne) value is null');
		}

		$show = $this->fieldOptions['relationship']['show'];

		print $value->$show;
        return null;
	}

}