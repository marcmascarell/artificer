<?php namespace Mascame\Artificer\Fields\Types;

use Carbon\Carbon;
use Form;

class DateTime extends \Mascame\Formality\Types\DateTime
{

    public function input()
    {
        return Form::text($this->name, Carbon::parse($this->value)->format('d-m-Y H:i:s'), $this->attributes);
    }

    public function show()
    {
        $date = Carbon::parse($this->value);

        $format = $this->getOption('format');

        return $date->format(($format) ? $format : 'd-m-Y H:i:s');
    }

}