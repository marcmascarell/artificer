<?php

namespace Mascame\Artificer\Fields\Types\Relations;

use Input;

class hasOne extends Relation
{
    protected $id;

    /**
     * @return array|bool|mixed|null
     */
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

        return false;
    }

    protected function getData()
    {
        return \View::getShared()['data'];
    }

    public function input()
    {
        $data = $this->getRelatedInstance()->all(
            (is_string($this->getShownProperty())) ? ['id', $this->getShownProperty()] : ['*']
        )->toArray();

        $this->select($data, $this->getShownProperty());
        $this->buttons();
    }

    public function show($value = null)
    {
        $value = ($value) ?: $this->default;

        if (! $value) {
            return '<em>(none)</em>';
        }

        $show = $this->getShownProperty();

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

        echo $value->{$this->getShownProperty()};
    }
}
