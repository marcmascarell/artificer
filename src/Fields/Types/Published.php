<?php namespace Mascame\Artificer\Fields\Types;

class Published extends \Mascame\Formality\Types\Checkbox
{
    public function show()
    {
        if ($this->value) {
            ?>
            <div class="text-center">
                <i class="fa fa-globe" title="Published"></i>
            </div>
        <?php

        }
    }
}
