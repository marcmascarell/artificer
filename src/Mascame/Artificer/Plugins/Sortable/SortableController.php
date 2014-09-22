<?php namespace Mascame\Artificer\Plugins\Sortable;

use Redirect;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Notification;

class SortableController extends Artificer {

	public $old_id;
	public $new_id;
	public $item_id;

	public function updateSort($old, $new)
	{

		if ($this->old_id != $old) {
			$move_item = $this->model->where('sort_id', '=', $old)->first();
			$move_item->sort_id = $new;
			$move_item->save();
		}

	}

	public function sort($modelName, $old_sort_id, $new_sort_id)
	{
		$this->old_id = $old_sort_id;
		$this->new_id = $new_sort_id;

		$item = $this->model->where('sort_id', '=', $this->old_id)->first();

		if (!empty($item)) {
			$item->sort_id = 0;

			$direction = ($old_sort_id < $new_sort_id) ? 'bigger' : 'smaller';

			if ($direction == 'bigger') {
				while ($old_sort_id <= $new_sort_id) {
					$new = $old_sort_id - 1;
					$this->updateSort($old_sort_id, $new);
					$old_sort_id ++;
				}
			} else {
				while ($old_sort_id >= $new_sort_id) {
					$this->updateSort($old_sort_id, $old_sort_id + 1);
					$old_sort_id --;
				}
			}

			$item->sort_id = $this->new_id;
			$item->save();

			Notification::success('<b>Success!</b> The table has been reordered!', true);
		}

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
	}

    public function handleDeletedRow($modelName, $old_id)
    {
        $last = $this->getLastSorted();

        $item = $this->model->find($old_id);

        $this->sort($modelName, $item->sort_id, $last->sort_id);
    }

    public function getLastSorted() {
        return $this->model->orderby('sort_id', 'desc')->first();
    }

}