<?php

return array(
	'plugin' => 'Mascame\Artificer\Plugins\Pagination\PaginationPlugin',

	'routes' => function() {
		Route::get('model/{slug}', array('as' => 'admin.all', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@all'));
		Route::post('model/{slug}/pagination', array('as' => 'admin.pagination', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@paginate'));
	},

    'view' => 'admin::plugins.pagination.pagination',
    'per_page' => 15
);