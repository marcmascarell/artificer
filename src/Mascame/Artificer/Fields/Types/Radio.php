<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class Radio extends Field {

	public function input()
	{
		return Form::radio($this->name, $this->value, false, $this->attributes->all());
	}

}