<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Password extends \Mascame\Formality\Types\Password
{

    public function input()
    {
        return Form::password($this->name, $this->attributes);
    }

    public function show()
    {
        return $this->hidden();
    }

}