<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
use HTML;


class Link extends Text {

	public function show()
	{
		return HTML::link($this->value);
	}
}