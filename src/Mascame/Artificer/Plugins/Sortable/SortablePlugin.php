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
        });

		//		$modified = 0;
		//		Event::listen('eloquent.saved: *', function ($model) {
		//			$original = $model->getOriginal();
		//
		//			if ($original->sort_id) {
		//				\Session::flash('artificer.plugin.sortable.need_resort', true);
		//			}
		//		});

		//		\Illuminate\Database\Eloquent\Model::saving(function($model) {
		//			dd($model);
		//		});

		//		\Illuminate\Database\Eloquent\Model::saved()::saved(function($model) {
		//            $original = $model->getOriginal();
		//dd($model);
		//            if ($model->sort_id) {
		//                $sortable = new SortableController();
		//                $sortable->sort(Model::getCurrent(),
		//                    $original['sort_id'],
		//                    $model->sort_id);
		//            }
		//
		//        });

    }
}
