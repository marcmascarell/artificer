<?php

return array(
	'plugin' => 'Mascame\Artificer\Plugins\Sortable\SortablePlugin',

	'routes' => function () {
		Route::post('model/{slug}/sort/{old_id}/{new_id}', array('as' => 'admin.sort', 'uses' => '\Mascame\Artificer\Plugins\Sortable\SortableController@sort'));
	}
);