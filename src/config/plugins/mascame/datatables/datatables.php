<?php

return array(
	'plugin' => 'Mascame\Artificer\Plugins\Datatables\DatatablesPlugin',

	'routes' => function () {
		Route::get('plugin/{slug}/page/configuration', array('as' => 'artificer.plugin.datatables.configuration', 'uses' => '\Mascame\Artificer\Plugins\Datatables\DatatablesController@configuration'));
	}
);