<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Password extends Field
{
    public $filterable = false;

    protected function input()
    {
        return Form::password($this->name, $this->attributes);
    }

    public function show()
    {
        return 'hidden';
    }
}
