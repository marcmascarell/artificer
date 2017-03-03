<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Password extends Field
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
