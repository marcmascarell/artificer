<?php namespace Mascame\Artificer\Fields;

use Illuminate\Database\Query\Builder;

trait Filterable
{

    /**
     * @param $query Builder
     * @param $value
     * @return mixed
     */
    public function filter($query, $value)
    {
        return $query->where($query, $value);
    }

    /**
     * @return bool
     */
    public function displayFilter()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function hasFilter()
    {
        return ($this->displayFilter()) ? true : false;
    }
}