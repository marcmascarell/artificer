<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\BaseModelController;
use Redirect;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Notification;

class SortableController extends BaseModelController {

	public $old_id;
	public $new_id;
	public $item_id;
	public $plugin;

	public function __construct()
	{
		parent::__construct();

		$this->plugin = $this->getPlugin('mascame/sortable');
	}

	public function updateSort($old, $new)
	{
		$sort_column = $this->plugin->sort_column;

		if ($this->old_id != $old) {
			$move_item = $this->model->where('sort_id', '=', $old)->first();
			$move_item->$sort_column = $new;
			$move_item->save();
		}

	}

	public function sort($modelName, $old_sort_id, $new_sort_id)
	{
		$this->old_id = $old_sort_id;
		$this->new_id = $new_sort_id;
		$sort_column = $this->plugin->sort_column;

		$item = $this->model->where($this->plugin->sort_column, '=', $this->old_id)->first();

		if (!empty($item)) {
			$item->$sort_column = 0;

            $direction = ($old_sort_id < $new_sort_id) ? 'bigger' : 'smaller';

			$this->reorder($direction, $old_sort_id, $new_sort_id);

			$item->$sort_column = $this->new_id;
			$item->save();

			Notification::success('<b>Success!</b> The table has been reordered!', true);
		}

		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
	}

    /**
     * @param $old_sort_id
     * @param $new_sort_id
     */
    protected function reorder($direction, $old_sort_id, $new_sort_id)
    {
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
    }

	public function handleDeletedRow($modelName, $old_id)
	{
		$sort_column = $this->plugin->sort_column;
		$last = $this->getLastSorted();

		$item = $this->model->find($old_id);

		$this->sort($modelName, $item->$sort_column, $last->$sort_column);
	}

	public function getLastSorted()
	{
		return $this->model->orderby($this->plugin->sort_column, 'desc')->first();
	}

}