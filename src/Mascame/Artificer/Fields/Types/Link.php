<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
use HTML;


class Link extends Text {

	public function show($value = null)
	{
		return HTML::link($this->getValue($value));
	}
}