<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Input;

//use Mascame\Artificer\Widgets\FocalPoint;


class Text extends \Mascame\Formality\Types\Text
{

    public function input()
    {
        return Form::text($this->name, $this->value, $this->attributes);
    }

    public function guarded()
    {
        return "<div>" . $this->value . "</div>";
    }

    public function displayFilter()
    {
        return Form::text($this->name, Input::old($this->name), $this->attributes);
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%' . $value . '%');
    }
}