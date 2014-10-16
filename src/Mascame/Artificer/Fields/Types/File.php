<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;

class File extends AbstractField {

	public function input()
	{
		return Form::file($this->name);
	}

}