<?php

use Illuminate\Support\Facades\Paginator;
use \Mascame\Artificer\Options\AdminOption;

function isHidden($key, $hidden)
{
	return (!isset($hidden) || (isset($hidden) && !in_array($key, $hidden))) ? true : false;
}

function getSort($table_name, $sort)
{
	$sort_table = $table_name;
	$sort_dir = 'asc';

	if ($sort != null && !empty($sort)) {
		$sorted_table = isset($sort['column']) ? $sort['column'] : null;
		$sort_dir = isset($sort['direction']) ? $sort['direction'] : null;

		if ($sorted_table == $table_name) {
			if ($sort_dir == 'asc') {
				$sort_dir = 'desc';
			} else {
				$sort_dir = 'asc';
			}
		}
	}

	return array('sort_by' => $sort_table, 'direction' => $sort_dir, 'page' => Input::get('page'));
}

function getSortIcon($table_name, $sort)
{
	if ($sort['column'] == $table_name) {
		if ($sort['direction'] == 'desc') {
			$icon = AdminOption::get('icons.sort-down');
		} else {
			$icon = AdminOption::get('icons.sort-up');
		}

		return '<i class="' . $icon . '"></i>';
	}

	return null;
}

HTML::macro('table', function ($model, $data = array(), $fields, $options, $sort,
                               $showView = true, $showEdit = true, $showDelete = true ) {
	?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped datatable"
			   data-page="<?= Paginator::getCurrentPage() ?>"
			   data-start="<?= $data[0]->sort_id ?>"
			   data-sort-url="<?= URL::route("admin.model.sort", array($model['route'], 'replace_old_id', 'replace_new_id')) ?>">
			<thead>
			<tr>
				<?php foreach ($fields as $field) {
					if ($field->isListed() && !($field->isHiddenList())) {
						?>
						<th>
							<a href="<?= URL::current() . '?' . http_build_query(getSort($field->name, $sort)) ?>">
								<?= Str::title($field->title) ?>

								<?= getSortIcon($field->name, $sort) ?>
							</a>
						</th>
					<?php
					}
				} ?>


				<?php if ($showEdit || $showDelete || $showView) { ?>
					<th></th>
				<?php } ?>
			</tr>
			</thead>

			<tbody class="sortable">

			<?php
			foreach ($data as $d) {
				?>
				<tr data-id="<?= $d->id ?>" data-sort-id="<?= $d->sort_id ?>">

					<?php foreach (array_keys($fields) as $key) {
						if ($fields[$key]->isListed() && !($fields[$key]->isHiddenList())) {
							?>
							<td>
								<?php
								if ($fields[$key]->isRelation()) {
									$method = $fields[$key]->relation->getMethod();
									$fields[$key]->display($d->$method);
								} else {
									print $fields[$key]->display($d->$key);
								}
								?>
							</td>
						<?php
						}
					} ?>

					<?php if ($showEdit || $showDelete || $showView) { ?>
						<td class="text-center">
							<div class="btn-group">
								<?php if ($showEdit) { ?>
									<a href="<?= route('admin.model.edit', array('slug' => $model['route'], 'id' => $d->id), $absolute = true) ?>"
									   type="button" class="btn btn-default">
										<i class="<?= AdminOption::get('icons.edit') ?>"></i>
									</a>
								<?php } ?>

								<?php if ($showView) { ?>
									<a href="<?= route('admin.model.show', array('slug' => $model['route'], 'id' => $d->id), $absolute = true) ?>" type="button"
									   class="btn btn-default">
										<i class="<?= AdminOption::get('icons.show') ?>"></i>
									</a>
								<?php } ?>

								<?php if ($showDelete) { ?>
									<a data-method="delete" data-token="<?= csrf_token() ?>"
									   href="<?= route('admin.model.destroy', array('slug' => $model['route'], 'id' => $d->id), $absolute = true) ?>"
									   type="button" class="btn btn-default">
										<i class="<?= AdminOption::get('icons.delete')  ?>"></i>
									</a>
								<?php } ?>
							</div>
						</td>

					<?php } ?>
				</tr>
			<?php } ?>

			</tbody>
		</table>
	</div>
<?php
});
