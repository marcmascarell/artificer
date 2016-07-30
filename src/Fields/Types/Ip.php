<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Ip extends Text
{

    public function input()
    {
        if (! isset($this->attributes['pattern'])) {
            $patterns = [
                '((^|:)([0-9a-fA-F]{0,4})){1,8}$|((^|\.)((25[0-5])', // v6
                '(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$' // v4
            ];

            $this->attributes['pattern'] = join('|', $patterns);
        }

        return parent::input();
    }
}