<?php

namespace Mascame\Artificer\Fields\Types;

use HTML;

class Link extends \Mascame\Formality\Types\Link
{
    public function show()
    {
        return HTML::link($this->value);
    }
}
