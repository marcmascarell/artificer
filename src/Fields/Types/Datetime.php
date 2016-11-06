<?php

namespace Mascame\Artificer\Fields\Types;

use Carbon\Carbon;
use Form;

class Datetime extends \Mascame\Formality\Types\DateTime
{
    public function input()
    {
        if ($this->value) {
            $this->value = Carbon::parse($this->value)->format('d-m-Y H:i:s');
        }

        return Form::text($this->name, $this->value, $this->attributes);
    }

    public function show()
    {
        $date = Carbon::parse($this->value);

        $format = $this->getOption('format');

        return $date->format(($format) ? $format : 'd-m-Y H:i:s');
    }
}
