<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Mascame\Artificer\Fields\Field;
use Mascame\Artificer\Model;
use URL;
use Route;

class Relation extends Field {

	public $modelObject;
	public $model;
	public $relation;
	public $fields;
	public $createURL;

	public function editURL($model_route, $id) {
		return URL::route('admin.model.edit', array('slug' => $model_route, 'id' => $id));
	}

	public function createURL($model_route) {
		return URL::route('admin.model.create', array('slug' => $model_route));
	}

	public function relationModal()
	{
		?>
		<div class="text-right">
			<div class="btn-group">
				<button class="btn btn-default" data-toggle="modal"
						data-url="<?=$this->createURL?>"
						data-target="#form-modal-<?= $this->model['route'] ?>">
					<i class="glyphicon glyphicon-plus"></i>
				</button>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade standalone" id="form-modal-<?= $this->model['route'] ?>" tabindex="-1" role="dialog"
			 aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
								class="sr-only">Close</span></button>
						<h4 class="modal-title">Loading Form</h4>
					</div>
					<div class="modal-body">
						<div class="alert alert-info" role="alert"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</div>
					</div>
					<div class="hidden default-modal-body">
						<div class="alert alert-info" role="alert"><i class="fa fa-circle-o-notch fa-spin"></i> Loading</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			var $modal = $('#form-modal-<?=$this->model['route']?>');
			var $modal_body = $(".modal-body");
			var url = null;

			$modal.on('show.bs.modal', function (e) {
				url = $(e.relatedTarget).data('url');
				url += " .right-side";
			});

			$modal.on('shown.bs.modal', function () {
				$modal_body.load(url, function () {
					var title = $(".modal-body h1").html();

					$('.modal-title').html(title);

					var $form = $('.modal-body form');

					$form.prepend('<input type="hidden" name="_standalone" value="<?=$this->model['route']?>">');

                    <?php if (Route::currentRouteName() == 'admin.model.create') { ?>
                    $form.prepend('<input type="hidden" name="_set_relation_on_create" value="<?=Model::getCurrent()?>">');
                    $form.prepend('<input type="hidden" name="_set_relation_on_create_foreign" value="<?=$this->relation['foreign']?>">');
                    <?php } ?>

					$form.submit(function (e) {
						e.preventDefault();

						$.post($form.attr('action'), $form.serialize(), function (data) {
							if (typeof data === 'string') {
								// validation errors
								$(".modal-body").html(data);
							} else if (typeof data === 'object') {
								refreshRelation();
								$modal.modal('hide');
							} else {
								alert('Something is wrong.');
							}
						});
					});
				});
			});

			$modal.on('hidden.bs.modal', function (e) {
				$modal_body.empty();
				$modal_body.html($('.default-modal-body').html())
			});

			function refreshRelation() {
				var $relation = $('[data-refresh-field]');

				$relation.load($relation.data('refresh-field'), function () {
//					$(this).data('refresh-relation')
				});
			}

		</script>
	<?php
	}
}