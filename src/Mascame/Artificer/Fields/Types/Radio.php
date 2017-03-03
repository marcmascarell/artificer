<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Radio extends Field
{
    public function input()
    {
        return Form::radio($this->name, $this->value, false, $this->attributes->all());
    }
}
