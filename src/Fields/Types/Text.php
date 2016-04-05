<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Text extends \Mascame\Formality\Types\Text
{

    public function input()
    {
        return Form::text($this->name, $this->value, $this->attributes);
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%' . $value . '%');
    }
}