<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Input;
use Mascame\Formality\Type\Type;

//use Mascame\Artificer\Widgets\FocalPoint;


class Text extends Type
{

    public function input()
    {
        return Form::text($this->name, $this->value, $this->attributes->all());
    }

    public function guarded()
    {
        return "<div>" . $this->value . "</div>";
    }

    public function displayFilter()
    {
        return Form::text($this->name, Input::old($this->name), $this->attributes->all());
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%' . $value . '%');
    }
}