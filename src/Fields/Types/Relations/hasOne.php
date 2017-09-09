<?php namespace Mascame\Artificer\Fields\Types\Relations;

use URL;
use Form;
use Input;
use Request;
use Illuminate\Support\Str;

class hasOne extends Relation
{
    protected $id;

    public function guessRelatedMethod()
    {
        // case 'model_id'

        $method = str_replace('_id', '', $this->name);

        if ($this->modelHasMethod($method)) {
            return $method;
        }

        // case 'my_current_model_id'
        $method = explode('_', $this->name);
        $method = isset(array_reverse($method)[1]) ? array_reverse($method)[1] : null;

        if ($this->modelHasMethod($method)) {
            return $method;
        }
    }

    protected function select($data, $show)
    {
        $select = [];
        foreach ($data as $d) {
            if (is_array($show)) {
                $value = '';
                foreach ($show as $show_key) {
                    $value .= Str::title($show_key).': '.$d[$show_key];

                    if (end($show) != $show_key) {
                        $value .= ' | ';
                    }
                }
            } elseif (is_callable($show)) {
                $value = $show($d);
            } else {
                $value = $d[$show];
            }

            $select[$d['id']] = $value;
        }

        if (Request::has($this->name)) {
            $this->id = Request::get($this->name);
        } else {
            if (isset($this->value->id)) {
                $this->id = $this->value->id;
            } else {
                $this->id = $this->value;
            }
        }

        echo \Form::select($this->name, ['0' => Request::ajax() ? '(current)' : '(none)'] + $select, $this->id,
            $this->attributes);
    }

    protected function buttons()
    {
        // Todo: $this->showFullField ?
//        if (!Request::ajax() || $this->showFullField) {

        if (! Request::ajax()) {
            $new_url = \URL::route('admin.model.create', ['slug' => $this->relatedModel->route]);
            $edit_url = \URL::route('admin.model.edit', ['slug' => $this->relatedModel->route, 'id' => ':id:']); ?>
            <br>
            <div class="text-right">
                <div class="btn-group">
                    <button class="btn btn-default" data-toggle="modal"
                            data-url="<?= $edit_url ?>"
                            data-target="#form-modal-<?= $this->relatedModel->route ?>">
                        <i class="fa fa-edit"></i>
                    </button>

                    <button class="btn btn-default" data-toggle="modal"
                            data-url="<?= $new_url ?>"
                            data-target="#form-modal-<?= $this->relatedModel->route ?>">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <?php

//            $this->relationModal($this->relatedModel['route'], $this->id);
        }
    }

    protected function getData()
    {
        return \View::getShared()['data'];
    }

    public function input()
    {
        $data = $this->getRelatedInstance()->all(
            (is_string($this->getShow())) ? ['id', $this->getShow()] : ['*']
        )->toArray();

        $this->select($data, $this->getShow());
        $this->buttons();
    }

    public function show($value = null)
    {
        $value = ($value) ?: $this->value;

        if (! $value) {
            return '<em>(none)</em>';
        }

        $show = $this->getShow();

        if (! is_object($value)) {
            $data = $this->getRelatedInstance()->findOrFail($value);

            if (! $data) {
                return '(none)';
            }

            if (is_array($show)) {
                foreach ($show as $item) {
                    echo $data->$item.'<br>';
                }

                return;
            } elseif (is_callable($show)) {
                return $show($data);
            } else {
                return $data->$show;
            }
        }

        if (! $value) {
            throw new \Exception('The (hasOne) value is null');
        }

        echo $value->{$this->getShow()};
    }
}
