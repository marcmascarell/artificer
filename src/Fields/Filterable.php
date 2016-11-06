<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Database\Query\Builder;
use Mascame\Formality\Field\FieldInterface;

trait Filterable
{
    /**
     * @var FieldInterface
     */
    public $field;

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
     * @return mixed
     */
    public function displayFilter()
    {
        $this->value = \Request::old($this->name);

        return $this->output();
    }

    /**
     * By default it has filter, use hasFilter method if you want to specify something.
     *
     * @return bool
     */
    public function hasFilter()
    {
        if (property_exists($this, 'filterable')) {
            return $this->filterable;
        }

        return true;
    }
}
