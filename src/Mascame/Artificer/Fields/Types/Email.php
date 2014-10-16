<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;
use HTML;

class Email extends AbstractField {

	public function input()
	{
		return Form::email($this->name, $this->value, $this->attributes->all());
	}

	public function show()
	{
		return HTML::mailto($this->value, $this->value);
	}

}