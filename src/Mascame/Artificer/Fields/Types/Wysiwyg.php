<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\ArtificerWidgets\CKeditor\CKeditor;

class Wysiwyg extends Textarea {

	public function boot()
	{
		$this->addWidget(new CKeditor());
		$this->attributes->add(array('class' => 'form-control ckeditor'));
	}
}