<?php namespace Mascame\Artificer\Fields;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Input;

trait Filterable
{

    /**
     * @param $query Builder
     * @param $value
     * @return mixed
     */
    public function filter($query, $value)
    {
        return $query->where($this->name, $value);
    }

    /**
     * @return bool
     */
    public function displayFilter()
    {
        $this->value = Input::old($this->name);

        return $this->output();
    }

    /**
     * @return bool
     */
    public function hasFilter()
    {
        $hasFilterMethod = method_exists($this->field, 'hasFilter');

        return ($hasFilterMethod) ? $this->field->hasFilter() : true;
    }
}