<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Enum extends Select {

	public function input()
	{
		$values = $this->options->getExistent('values', array());

		return Form::select($this->name, $values, $this->value, $this->attributes->all());
	}
}