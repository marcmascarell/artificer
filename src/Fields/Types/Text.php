<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Input;

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

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%' . $value . '%');
    }
}