<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Text extends Field
{
    public function input()
    {
        return Form::text($this->name, $this->value, $this->attributes);
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%'.$value.'%');
    }
}
