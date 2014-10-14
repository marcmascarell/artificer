<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Plugin\Plugin;
use Event;
use App;

class SortablePlugin extends Plugin {

	public $sort_column = 'sort_id';

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Sortable';
		$this->description = 'Ultra simple sort of records (using the db column <b>' . $this->sort_column . '</b>).';
		$this->author = 'Marc Mascarell';
	}

	public function boot()
	{
		Event::listen(array('artificer.before.destroy'), function ($item) {
			$sortable = new SortableController();
			$sortable->handleDeletedRow($item['model'], $item['id']);
		});

		$this->addHooks();
	}

	public function addHooks()
	{
		$model = App::make('artificer-model');

		if (is_array($model->columns) && in_array($this->sort_column, $model->columns)) {
			Event::listen(array('artificer.view.head-scripts'), function () {
				?>
				<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
				<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
				<script
					src="<?php print asset('packages/mascame/artificer/plugins/mascame/sortable/sortable.js') ?>"></script>
			<?php
			});
		}
	}
}
