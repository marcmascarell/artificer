<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use HTML;
use Input;
use Mascame\Artificer\Fields\Field;

class Email extends Field
{
    public function input()
    {
        return Form::email($this->name, $this->value, $this->attributes->all());
    }

    public function show()
    {
        return HTML::mailto($this->value, $this->value);
    }

    public function displayFilter()
    {
        return Form::text($this->name, Input::old($this->name), $this->attributes->all());
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%'.$value.'%');
    }
}
