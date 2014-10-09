<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
use HTML;

class Email extends Field {

	public function input()
	{
		return Form::email($this->name, $this->value, $this->getAttributes());
	}

	public function show()
	{
		return HTML::mailto($this->value, $this->value);
	}

}