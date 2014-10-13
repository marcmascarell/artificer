<?php

return array(
	'plugin'   => 'Mascame\Artificer\Plugins\Pagination\PaginationPlugin',

	'routes'   => function () {
		Route::get('model/{slug}', array('as' => 'admin.model.all', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@all'));
		Route::post('model/{slug}/pagination', array('as' => 'admin.model.pagination', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@paginate'));
	},

	'view'     => 'artificer::plugins.pagination.pagination',
	'per_page' => 15
);