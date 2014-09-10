<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
use HTML;


class Link extends Field {


	public function input()
	{
		return Form::text($this->name, $this->value, $this->getAttributes());
	}

	public function show($value = null)
	{
		return HTML::link($this->getValue($value));
	}
}