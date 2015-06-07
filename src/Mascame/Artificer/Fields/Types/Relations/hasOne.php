<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Form;
use Input;
use URL;
use Request;

class hasOne extends Relation {

    protected $id;

    protected function select($data, $show) {
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
            $this->id = Input::get($this->name);
        } else if (isset($this->value->id)) {
            $this->id = $this->value->id;
        } else {
            $this->id = $this->value;
        }

        print Form::select($this->name, array('0' => Request::ajax() ? '(current)' : '(none)') + $select, $this->id, $this->attributes->all());
    }

    protected function buttons() {
        if (!Request::ajax() || $this->showFullField) {
            $new_url = \URL::route('admin.model.create', array('slug' => $this->model['route']));
            $edit_url = \URL::route('admin.model.edit', array('slug' => $this->model['route'], 'id' => ':id:'));
            ?>
            <br>
            <div class="text-right">
                <div class="btn-group">
                    <button class="btn btn-default" data-toggle="modal"
                            data-url="<?=$edit_url?>"
                            data-target="#form-modal-<?= $this->model['route'] ?>">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button class="btn btn-default" data-toggle="modal"
                            data-url="<?=$new_url?>"
                            data-target="#form-modal-<?= $this->model['route'] ?>">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <?php

            $this->relationModal($this->model['route'], $this->id);
        }
    }

	public function input()
	{
        if ( ! $this->relation->getRelatedModel()) {
            throw new \Exception("Missing relation in config for '{$this->name}'.");
        }

		$this->model = $this->modelObject->schema->models[$this->relation->getRelatedModel()];
        $this->model['class'] = $modelClass = $this->modelObject->schema->getClass($this->model['name']);
        $show = $this->relation->getShow();

        $show_query = (is_array($show)) ? array('*') : array('id', $show);
		$data = $modelClass::all($show_query)->toArray();

        $this->select($data, $show);
        $this->buttons();
	}

	public function show($value = null)
	{
		$value = ($value) ?: $this->value;

		if (!$value) return "<em>(none)</em>";

        $show = $this->relation->getShow();

        if (!is_object($value)) {
            $model = '\\' . $this->relation->getRelatedModel();

            $data = $model::find($value);

            if (!$data) return '(none)';

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