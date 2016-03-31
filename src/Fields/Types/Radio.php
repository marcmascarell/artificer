<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Radio extends \Mascame\Formality\Types\Radio
{

    public function input()
    {
        return Form::radio($this->name, $this->value, false, $this->attributes);
    }

}