<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Illuminate\Support\Str;
use Mascame\Formality\Types\File;

class Image extends File
{
    public function boot()
    {
        //		$this->addWidget(new FocalPoint());
    }

    public function input()
    {
        if ($this->value != null) {
            ?>
            <div data-box class="focal_box">
                <?= $this->show() ?>
                <div data-point class="focal_point"></div>
            </div>

            <div data-position class="focal_position"></div>
        <?php

        }

        echo Form::file($this->name);
    }

    public function show()
    {
        $value = $this->value;

        if (! $value) {
            return '<div class="well well-sm">No file</div>';
        }

        if (! Str::startsWith($value, ['https://', 'http://'])) {
            $value = '/uploads/'.$value;
        } ?>

        <div class="thumbnail">
            <img style="display: block; margin: auto;height:auto; width:auto; max-width:100px; max-height:100px;"
                 src="<?= $value ?>" height="100"/>
        </div>
    <?php

    }
}
