<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Mascame\Artificer\Fields\Field;
use Mascame\Artificer\Model\Model;
use URL;
use Route;

class Relation extends Field {

	/**
	 * @var Model;
	 */
	public $modelObject;
	public $model;
	public $fields;
	public $createURL;

    public function boot()
    {
        $this->modelObject = \App::make('artificer-model');
    }

	public function editURL($model_route, $id) {
		return URL::route('admin.model.edit', array('slug' => $model_route, 'id' => $id));
	}

	public function createURL($model_route) {
		return URL::route('admin.model.create', array('slug' => $model_route));
	}

	public function relationModal($relatedModelRouteName)
	{
		?>


		<!-- Modal -->
		<div class="modal fade standalone" id="form-modal-<?= $relatedModelRouteName ?>" tabindex="-1" role="dialog"
			 aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
								class="sr-only">Close</span></button>
						<h4 class="modal-title">Loading Form</h4>
					</div>

					<div id="modal-body-<?= $relatedModelRouteName ?>" class="modal-body">
						<div class="alert alert-info" role="alert"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</div>
					</div>

					<div class="hidden default-modal-body">
						<div class="alert alert-info" role="alert"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			var $modal_<?=$relatedModelRouteName?> = $('#form-modal-<?=$relatedModelRouteName?>');
			var $modal_body_<?=$relatedModelRouteName?> = $("#modal-body-<?=$relatedModelRouteName?>");
			var url = null;

			$modal_<?=$relatedModelRouteName?>.on('show.bs.modal', function (e) {
				url = $(e.relatedTarget).data('url');
//				url += " .right-side";
			});

			$modal_<?=$relatedModelRouteName?>.on('shown.bs.modal', function () {
				$modal_body_<?=$relatedModelRouteName?>.load(url, function () {
					var title = $modal_body_<?=$relatedModelRouteName?>.find('h1').html();

					$('.modal-title').html(title);

					var $form = $modal_body_<?=$relatedModelRouteName?>.find('form');
//                    $form.attr('action', url);
//                    $form.attr('method', 'POST');

                    $form.submit(function (e) {
						e.preventDefault();
                        $form.prepend('<input type="hidden" name="_standalone" value="<?=$relatedModelRouteName?>">');

                        <?php if (Route::currentRouteName() == 'admin.model.create') { ?>
                        $form.prepend('<input type="hidden" name="_set_relation_on_create" value="<?=Model::getCurrent()?>">');
                        $form.prepend('<input type="hidden" name="_set_relation_on_create_foreign" value="<?=$this->relation['foreign']?>">');
                        <?php } ?>

//                        console.log($form.serialize());
						$.post($form.attr('action'), $form.serialize(), function (data) {
							if (typeof data === 'string') {
								// validation errors
                                $modal_body_<?=$relatedModelRouteName?>.html(data);
							} else if (typeof data === 'object') {
								refreshRelation();
								$modal_<?=$relatedModelRouteName?>.modal('hide');
							} else {
								alert('Something is wrong.');
							}
						});
					});
				});
			});

			$modal_<?=$relatedModelRouteName?>.on('hidden.bs.modal', function (e) {
				$modal_body_<?=$relatedModelRouteName?>.empty();
				$modal_body_<?=$relatedModelRouteName?>.html($('.default-modal-body').html())
			});

//			function refreshRelation() {
//				var $relation = $('[data-refresh-field]');
//                var name = $relation.attr('name');
//                var url = '<?php //URL::route('admin.model.field.edit', array('slug' => $relatedModelRouteName)) ?>//';
//
//                alert(url);
////				$relation.load(, function () {
//////					$(this).data('refresh-relation')
////				});
//			}

		</script>
	<?php
	}
}