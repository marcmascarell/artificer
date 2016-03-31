<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Select extends \Mascame\Formality\Types\Select
{

    public function input()
    {
        return Form::select($this->name, $this->value, false);
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