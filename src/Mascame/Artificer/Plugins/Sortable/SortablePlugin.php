<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Plugin;
use Event;

class SortablePlugin extends Plugin {

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Sortable';
		$this->description = 'Ultra simple sort of records (using a db column). <b>Does not work when there are deleted rows</b>';
		$this->author = 'Marc Mascarell';
	}

    public function boot() {
        Event::listen(array('artificer.after.destroy'), function ($item) {

        });
    }
}