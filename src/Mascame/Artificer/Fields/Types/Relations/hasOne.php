<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Form;
use Input;
use URL;
use Request;

class hasOne extends Relation {

	public function boot()
	{
		//$this->addWidget(new Chosen());
		$this->attributes->add(array('class' => 'chosen form-control'));
	}

	public function input()
	{
		$modelName = $this->relation->getRelatedModel();
		$model = \App::make('artificer-model');
		$modelClass = '\\' . $modelName;
        $show = $this->relation->getShow();

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

		print Form::select($this->name, array('0' => '(none)') + $select, $id, $this->attributes->all());

        if (!Request::ajax()) {
            $new_url = \URL::route('admin.model.create', array('slug' => $model->models[$modelName]['route']));
            $edit_url = \URL::route('admin.model.edit', array('slug' => $model->models[$modelName]['route'], 'id' => $id));
            ?>

            <br>
            <div class="text-right">
                <div class="btn-group">
                    <a href="<?= $edit_url ?>" target="_blank" type="button" class="btn btn-default">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a href="<?= $new_url ?>" target="_blank" type="button" class="btn btn-default">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
        <?php
        }
	}

	public function show($value = null)
	{
		$value = ($value) ?: $this->value;

		if (!$value) return "<em>(none)</em>";

        $show = $this->relation->getShow();

        if (!is_object($value)) {
            $model = '\\' . $this->relation->getRelatedModel();

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

		$show = $this->options['relationship']['show'];

		print $value->$show;
        return null;
	}

}