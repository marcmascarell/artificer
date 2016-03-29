<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use HTML;
use Input;
use Mascame\Formality\Type\Type;

class Email extends \Mascame\Formality\Types\Email
{
    public function displayFilter()
    {
        return Form::text($this->name, Input::old($this->name), $this->attributes->all());
    }

    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%' . $value . '%');
    }
}