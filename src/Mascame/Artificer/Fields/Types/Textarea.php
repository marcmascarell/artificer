<?php

namespace Mascame\Artificer\Fields\Types;

use Form;

class Textarea extends Text
{
    public function input()
    {
        return Form::textarea($this->name, $this->value, $this->attributes->all());
    }

    public function guarded()
    {
        return '<div>'.$this->value.'</div>';
    }
}
