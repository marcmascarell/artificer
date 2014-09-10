<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Widgets\CKeditor\CKeditor;

class Wysiwyg extends Textarea {

	public function boot()
	{
		$this->addWidget(new CKeditor());
		$this->addAttributes(array('class' => 'form-control ckeditor'));
	}
}