<?php

return array(
	'plugin' => 'Mascame\Artificer\Plugins\Sortable\SortablePlugin',

	'routes' => function () {
		Route::post('model/{slug}/sort/{old_id}/{new_id}', array('as' => 'admin.model.sort', 'uses' => '\Mascame\Artificer\Plugins\Sortable\SortableController@sort'));
	},

	'sortable_column' => 'sort_id'
);