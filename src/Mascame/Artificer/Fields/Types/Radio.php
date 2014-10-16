<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;

class Radio extends AbstractField {

	public function input()
	{
		return Form::radio($this->name, $this->value, false, $this->attributes->all());
	}

}