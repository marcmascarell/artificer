<?php namespace Mascame\Artificer\Plugins\Sortable;

// This widget requires a column on database like "sort_id" to work
use Mascame\Artificer\Widgets\Widget;

class SortableWidget extends Widget {

	public function output()
	{
		?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

		<script>
			$(function() {
				var sortable_start_item = $("table").data('start');
				var sortable_url = $("table").data('sort-url');
				var sortable_start_pos = null;
				var sortable_end_pos = null;
				var new_url = null;

				$(".sortable").sortable({
					placeholder: "ui-state-highlight",
					start: function(event , ui) {
						sortable_start_pos = $(ui.item).data('sort-id');
					},
					update: function( event, ui ) {
						sortable_end_pos = ui.item.index() + sortable_start_item;
						new_url = sortable_url.replace("replace_old_id", sortable_start_pos);
						new_url = new_url.replace("replace_new_id", sortable_end_pos);

						$('#sort-submit').parent('form').attr('action', new_url).submit();
					}
				});

			});
		</script>
		<?php
	}

}