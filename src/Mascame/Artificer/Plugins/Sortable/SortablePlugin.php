<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Plugin;
use Event;

class SortablePlugin extends Plugin {

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Sortable';
		$this->description = 'Ultra simple sort of records (using a db column).';
		$this->author = 'Marc Mascarell';
	}

    public function boot() {
        Event::listen(array('artificer.before.destroy'), function ($item) {
            $sortable = new SortableController();
            $sortable->handleDeletedRow($item['model'], $item['id']);

            $sortable->successNotification();
        });
    }
}
