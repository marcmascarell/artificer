<?php namespace Mascame\Artificer\Fields\Types\Relations;

use HTML;

class hasMany extends Relation {

	public function boot()
	{
		//$this->addWidget(new Chosen());
		$this->addAttributes(array('class' => 'chosen'));
	}

	public function input()
	{
		$fields = array_get(\View::getShared(), 'fields');
		$id = $fields['id']->value;

		$options = $this->fieldOptions;
		$modelObject = \App::make('artificer-model');
		$modelName = $options['relationship']['model'];
		$foreign = $this->fieldOptions['relationship']['foreign'];
		$model = '\\' . $modelName;

		$data = $model::where($foreign, '=', $id)->get(array('id', $options['relationship']['show']))->toArray();

		$select = array();


			if (!empty($data)) {
				?>
				<ul class="list-group">
					<?php foreach ($data as $d) {
						$select[$d['id']] = $d[$options['relationship']['show']];

						$edit_url = \URL::route('admin.edit', array('slug' => $modelObject->models[$modelName]['route'], 'id' => $d['id']));
						?>
						<li class="list-group-item">
							<?= $d[$options['relationship']['show']] ?>
							&nbsp;
							<a href="<?= $edit_url ?>" target="_blank">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
						</li>
						<?php
					} ?>
				</ul>
				<?php
			} else {
				?><div class="well well-sm">No items yet</div><?php
			}

		?>
		<a href="<?= \URL::route('admin.create', array('slug' => $modelObject->models[$modelName]['route'])) ?>?<?= http_build_query(array($foreign => $id)) ?>"
		   target="_blank">
			<i class="fa fa-plus"></i>
			New
		</a>
<?php

//		return HTML::ul($select, $this->getAttributes());
	}

	public function show($values = null)
	{
		if (!$values->isEmpty()) {

			$show = $this->fieldOptions['relationship']['show'];

			foreach ($values as $value) {
				print $value->$show . "<br>";
			}
		}

		return null;
	}

}