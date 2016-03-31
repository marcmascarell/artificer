<?php namespace Mascame\Artificer\Fields\Types;

use Carbon\Carbon;
use Form;

class DateTime extends \Mascame\Formality\Types\DateTime
{

    public function input()
    {
        ?>
        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <?php print Form::text($this->name, $this->value, $this->attributes); ?>
        </div>
    <?php
    }

    public function show()
    {
        $date = Carbon::parse($this->value);

        $format = $this->getOption('format');

        return $date->format(($format) ? $format : 'd-m-Y H:i:s');
    }

}