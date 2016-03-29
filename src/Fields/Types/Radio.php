<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Formality\Type\Type;

class Radio extends Type
{

    public function input()
    {
        return Form::radio($this->name, $this->value, false, $this->attributes->all());
    }

}