<?php namespace Mascame\Artificer\Plugins\Sortable;

use Mascame\Artificer\Plugin;
use Event;
use App;
use Mascame\Notify\Notify;

class SortablePlugin extends Plugin {

    public $sort_column = 'sort_id';

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Sortable';
		$this->description = 'Ultra simple sort of records (using the db column <b>'.$this->sort_column . '</b>).';
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

                <script>
                    $(function () {
                        var sortable_start_item = $("table").data('start');
                        var sortable_url = $("table").data('sort-url');
                        var sortable_start_pos = null;
                        var sortable_end_pos = null;
                        var new_url = null;

                        $(".sortable").sortable({
                            placeholder: "ui-state-highlight",
                            start: function (event, ui) {
                                sortable_start_pos = $(ui.item).data('sort-id');
                            },
                            update: function (event, ui) {
                                sortable_end_pos = ui.item.index() + sortable_start_item;
                                new_url = sortable_url.replace("replace_old_id", sortable_start_pos);
                                new_url = new_url.replace("replace_new_id", sortable_end_pos);

                                $('#sort-submit').parent('form').attr('action', new_url).submit();
                            }
                        });

                    });
                </script>
            <?php
            });
        }
    }
}
