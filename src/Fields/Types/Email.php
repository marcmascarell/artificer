<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use HTML;
use Mascame\Artificer\Fields\Field;

class Email extends Field
{
    protected function input()
    {
        return Form::email($this->name, $this->value, $this->attributes);
    }

    public function show()
    {
        return HTML::mailto($this->value, $this->value);
    }

    public function displayFilter()
    {
        return Form::text($this->name, \Request::old($this->name), $this->attributes);
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%'.$value.'%');
    }
}
