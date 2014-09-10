<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Plugin;

class SortablePlugin extends Plugin {

	public function __construct($namespace, $model = null)
	{
		parent::__construct($namespace, __DIR__);
		$this->version = '1.0';
		$this->name = 'Sortable';
		$this->description = 'Ultra simple sort of records (using a db column). <b>Does not work when there are deleted rows</b>';
		$this->author = 'Marc Mascarell';
	}
}