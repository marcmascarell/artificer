<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class File extends Field {

	public function input()
	{
		return Form::file($this->name);
	}

}