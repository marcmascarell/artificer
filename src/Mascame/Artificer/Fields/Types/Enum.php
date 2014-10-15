<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Enum extends Select {

	public function input()
	{
		$values = $this->options['values'];

		return Form::select($this->name, array_combine($values, $values), $this->value, $this->attributes->all());
	}
}