<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Textarea extends Field
{
    protected function input()
    {
        return Form::textarea($this->name, $this->value, $this->attributes);
    }
}
