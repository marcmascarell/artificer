<?php namespace Mascame\Artificer\Plugins\Datatables;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\Plugin;
use Config;
use View;
use Image;
use Event;
use Route;

class DatatablesPlugin extends Plugin {

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Datatables';
		$this->description = 'Datatables for models';
		$this->author = 'Marc Mascarell';
		$this->options = array(
			'configuration' => array(
				'title' => 'Config',
				'icon'  => '',
				'route' => route('admin.plugin.datatables.configuration', $this->slug)
			)
		);
	}

    public function boot() {
        $this->addHooks();
    }

	public function addHooks()
	{
		Event::listen(array('head-scripts'), function () {
			?>
			<!-- DATA TABLES -->
			<link href="<?php print asset('packages/mascame/admin/plugins/datatables/dataTables.bootstrap.css') ?>"
				  rel="stylesheet" type="text/css"/>
		<?php
		});

		Event::listen(array('bottom-scripts'), function () {
			?>
			<!-- DATA TABES SCRIPT -->
			<script src="<?php print asset('packages/mascame/admin/plugins/datatables/jquery.dataTables.js') ?>"
					type="text/javascript"></script>
			<script src="<?php print asset('packages/mascame/admin/plugins/datatables/dataTables.bootstrap.js') ?>"
					type="text/javascript"></script>

			<!-- page script -->
			<script type="text/javascript">
				$(function () {
					$('.datatable').dataTable({
						"bPaginate": true,
						"bLengthChange": true,
						"bFilter": true,
						"bSort": true,
						"bInfo": true,
						"bAutoWidth": false
					});
				});
			</script>
		<?php
		});
	}

}