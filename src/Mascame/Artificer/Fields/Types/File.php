<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class File extends Field
{
    public function input()
    {
        return Form::file($this->name);
    }
}
