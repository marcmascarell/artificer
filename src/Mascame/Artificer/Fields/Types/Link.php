<?php

namespace Mascame\Artificer\Fields\Types;

use HTML;

class Link extends Text
{
    public function show()
    {
        return HTML::link($this->value);
    }
}
