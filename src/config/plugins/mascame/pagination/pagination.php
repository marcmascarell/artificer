<?php

return array(
	'plugin'   => 'Mascame\Artificer\Plugins\Pagination\PaginationPlugin',

	'routes'   => function () {
        Route::group(array('prefix' => 'model'), function () {
            Route::get('{slug}', array('as' => 'admin.model.all', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@all'));
            Route::post('{slug}/filter', array('as' => 'admin.model.filter', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@filter'));
            Route::post('{slug}/pagination', array('as' => 'admin.model.pagination', 'uses' => '\Mascame\Artificer\Plugins\Pagination\PaginationController@paginate'));
        });
	},

	'view'     => 'artificer::plugins.pagination.pagination',
	'per_page' => 15
);