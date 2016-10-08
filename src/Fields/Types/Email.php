<?php

namespace Mascame\Artificer\Fields\Types;

use Form;

class Email extends \Mascame\Formality\Types\Email
{
    public function displayFilter()
    {
        return Form::text($this->name, \Request::old($this->name), $this->attributes);
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%'.$value.'%');
    }
}
