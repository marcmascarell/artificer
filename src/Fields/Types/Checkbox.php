<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Checkbox extends Field
{
    protected function input()
    {
        $output = Form::hidden($this->name, 0);
        $output .= Form::checkbox($this->name, 1, $this->value, $this->attributes);

        return $output;
    }
}
