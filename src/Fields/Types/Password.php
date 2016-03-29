<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Formality\Type\Type;

class Password extends Type
{

    public function input()
    {
        return Form::password($this->name, $this->attributes->all());
    }

    public function show()
    {
        return $this->hidden();
    }

}