<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Select extends Field
{
    protected function input()
    {
        return Form::select($this->name, $this->getOption('choices', [
            'my options',
            'are awesome',
        ]), $this->value, $this->attributes);
    }

    public function outputRange($start, $end)
    {
        return Form::selectRange($this->name, $start, $end);
    }

    public function outputMonth()
    {
        return Form::selectMonth($this->name);
    }

    public function outputYear()
    {
        return Form::selectYear($this->name);
    }
}
