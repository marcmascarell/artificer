<?php

namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;

class Checkbox extends Field
{
    public function savingHook($model)
    {
        $name = $this->getName();
        $value = $model->{$name};

        if ($value === null) {
            $model->$name = 0;
        }
    }
}
