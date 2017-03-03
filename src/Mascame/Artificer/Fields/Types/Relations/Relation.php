<?php namespace Mascame\Artificer\Fields\Types\Relations;

use URL;
use Route;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Fields\Field;

class Relation extends Field
{
    /**
     * @var Model;
     */
    public $modelObject;
    public $model;
    public $fields;
    public $createURL;

    public function boot()
    {
        parent::boot();

        $this->modelObject = \App::make('artificer-model');
    }

    public function editURL($model_route, $id)
    {
        return URL::route('admin.model.edit', ['slug' => $model_route, 'id' => $id]);
    }

    public function createURL($model_route)
    {
        return URL::route('admin.model.create', ['slug' => $model_route]);
    }

    public function relationModal($relatedModelRouteName, $id = 0)
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
			$(function () {

				var $modal_<?=$relatedModelRouteName?> = $('#form-modal-<?=$relatedModelRouteName?>');
				var $modal_body_<?=$relatedModelRouteName?> = $("#modal-body-<?=$relatedModelRouteName?>");
				var url = null;
				var id = '<?=$id?>';
				var $field = $('[name="<?=$this->name?>"]');

				if ($field.is("select")) {
					$field.on('change', function() {
//						alert( this.value ); // or $(this).val()

						id = this.value;
					});
				}

				$modal_<?=$relatedModelRouteName?>.on('show.bs.modal', function (e) {
					url = $(e.relatedTarget).data('url');
					url = url.replace(':id:', id);
	//				url += " .right-side";
				});

				$modal_<?=$relatedModelRouteName?>.on('shown.bs.modal', function () {
					$modal_body_<?=$relatedModelRouteName?>.load(url, function () {
						var title = $modal_body_<?=$relatedModelRouteName?>.find('h1').html();

						$('.modal-title').html(title);

						var $form = $modal_body_<?=$relatedModelRouteName?>.find('form');
	//                    $form.attr('action', url);
	//                    $form.attr('method', 'POST');
						$form.prepend('<input type="hidden" name="_standalone" value="<?=$relatedModelRouteName?>">');
						$form.prepend('<input type="hidden" name="_standalone_origin" value="<?=$this->modelObject->getRouteName(Model::$current)?>">');
						$form.prepend('<input type="hidden" name="_standalone_origin_id" value="<?=$id?>">');

						<?php if (Route::currentRouteName() == 'admin.model.create') {
            ?>
						$form.prepend('<input type="hidden" name="_set_relation_on_create" value="<?=Model::getCurrent()?>">');
						$form.prepend('<input type="hidden" name="_set_relation_on_create_foreign" value="<?=$this->relation->getForeignKey()?>">');
						<?php 
        } ?>

						var action = $form.attr('action');

						$form.submit(function (e) {
							e.preventDefault();
							$.post(action, $form.serialize(), function (data) {

								if (typeof data === 'string') {
									// validation errors
									$modal_body_<?=$relatedModelRouteName?>.html(data);
								} else if (typeof data === 'object') {
									console.log('here');
									refreshRelation(data, '<?=$this->name?>');
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

				function refreshRelation(data, name) {
					var $relation = $('[name="'+name+'"]');
					var url = data.refresh;

					url = url.replace(':fieldName:', name);

					// After this call this whole modal will disappear
					$relation.parent('.form-group').load(url, function (responseText, textStatus, req) {
						if (textStatus == "error") {
							return "oh noes!!!!";
						} else {
							$("body").trigger("relationRefresh", {
								name: name,
								id: id
							});
						}
					});
				}
			});
		</script>
	<?php

    }
}
