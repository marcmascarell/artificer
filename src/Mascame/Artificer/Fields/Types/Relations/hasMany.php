<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Mascame\Artificer\Model\Model;
use Request;
use Route;
use Session;

// Todo: attach somehow the new created items to the a new item (which have not yet been created)

class hasMany extends Relation {

	public function boot()
	{
        parent::boot();
		//$this->addWidget(new Chosen());
		$this->attributes->add(array('class' => 'chosen'));
	}

	public function input()
	{
        $this->fields = array_get(\View::getShared(), 'fields');
		$id = $this->fields['id']->value;

		$modelName = $this->relation->getRelatedModel();
		$model = $this->modelObject->schema->models[$modelName];
		$model['class'] = $this->modelObject->schema->getClass($modelName);
		$this->model = $model;

        if ((Route::currentRouteName() == 'admin.model.create' || Route::currentRouteName() == 'admin.model.field')
            && Session::has('_set_relation_on_create_'.$this->modelObject->name)) {
            $relateds = Session::get('_set_relation_on_create_'.$this->modelObject->name);

            $related_ids = array();
            foreach ($relateds as $related) {
                $related_ids[] = $related['id'];
            }

            $data = $relateds[0]['modelClass']::whereIn('id', $related_ids)->get()->toArray();
        } else {
            $data = $model['class']::where($this->relation->getForeignKey(), '=', $id)->get(array('id', $this->relation->getShow()))->toArray();
        }

        $this->showItems($data);

		$this->createURL = $this->createURL($this->model['route']) . "?" . http_build_query(array($this->relation->getForeignKey() => $id, '_standalone' => 'true'));

		if (!Request::ajax()) {
			$this->relationModal();
		}
	}

	public function showItems($data)
	{
		if (!Request::ajax()) { ?>
			<div data-refresh-field="<?= \URL::route('admin.model.field',
				array('slug'  => $this->model['route'],
					  'id'    => ($this->fields['id']->value) ? $this->fields['id']->value : 0,
					  'field' => $this->name)) ?>">
		<?php }

			if (!empty($data)) { ?>
				<ul class="list-group">
					<?php foreach ($data as $item) {
						$this->addItem($item);
					} ?>
				</ul>
			<?php } else { ?>
				<div class="well well-sm">No items yet</div><?php
			}

		if (!Request::ajax()) { ?>
			</div>
		<?php }
	}

	public function addItem($item) {
		$edit_url = $this->editURL($this->model['route'], $item['id']).'?'. http_build_query(array('_standalone' => 'true'));
		?>
		<li class="list-group-item">
			<?= $item[$this->relation->getShow()] ?> &nbsp;

			<span class="right">
				<span class="btn-group">
					<button class="btn btn-default" data-toggle="modal"
							data-url="<?=$edit_url?>"
							data-target="#form-modal-<?= $this->model['route'] ?>">
						<i class="fa fa-edit"></i>
					</button>
					<a data-method="delete" data-token="<?= csrf_token() ?>"
					   href="<?= route('admin.model.destroy', array('slug' => $this->model['route'], 'id' => $item['id']), $absolute = true) ?>"
					   type="button" class="btn btn-default">
						<i class="fa fa-remove"></i>
					</a>
				</span>
			</span>

		</li>
		<?php
	}

	public function show($values = null)
	{
		if (isset($values) && !$values->isEmpty()) {
			$show = $this->relation->getShow();

			foreach ($values as $value) {
				print $value->$show . "<br>";
			}
		} else {
			print "<em>(none)</em>";
		}
	}

}