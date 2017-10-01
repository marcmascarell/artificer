<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use HTML;
use Mascame\Artificer\Fields\Field;

class Email extends Field
{
    public function show()
    {
        return HTML::mailto($this->default, $this->default);
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
