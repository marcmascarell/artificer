<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Plugin\AbstractPlugin;
use Event;
use App;

class SortablePlugin extends AbstractPlugin {

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
		$model = App::make('artificer-model');
		$sort_column = $this->options['sortable_column'];

		if (is_array($model->schema->columns) && in_array($this->$sort_column, $model->schema->columns)) {
			Event::listen(array('artificer.before.destroy'), function ($item) {
				$sortable = new SortableController();
				$sortable->handleDeletedRow($item['model'], $item['id']);
			});

			$this->addHooks();
		}
	}

	public function addHooks()
	{
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
