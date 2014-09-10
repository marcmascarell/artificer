<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class Checkbox extends Field {

	public function input()
	{
		return Form::checkbox($this->name, $this->value, false, $this->getAttributes());
	}

}