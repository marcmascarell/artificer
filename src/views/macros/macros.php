<?php

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
			$icon = 'fa-long-arrow-down';
		} else {
			$icon = 'fa-long-arrow-up';
		}

		return '<i class="fa ' . $icon . '"></i>';
	}

	return null;
}

HTML::macro('table', function ($model, $data = array(), $fields, $options, $sort) {
	$showEdit = true;
	$showDelete = true;
	$showView = true;
	?>
	<div class="table-responsive">
		<table id="table" class="table table-bordered table-striped"
			   data-page="<?= Paginator::getCurrentPage() ?>"
			   data-start="<?= $data[0]->sort_id ?>"
			   data-sort-url="<?= URL::route("admin.sort", array($model['route'], 'replace_old_id', 'replace_new_id')) ?>">
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

					<?php foreach ($model['columns'] as $key) {
						if ($fields[$key]->isListed() && !($fields[$key]->isHiddenList())) {
							?>
							<td>
								<?= $fields[$key]->show($d->$key) ?>
							</td>
						<?php
						}
					} ?>

					<?php if ($showEdit || $showDelete || $showView) { ?>
						<td class="text-center">
							<div class="btn-group">
								<?php if ($showEdit) { ?>
									<a href="<?= route('admin.edit', array('slug' => $model['route'], 'id' => $d->id), $absolute = true) ?>"
									   type="button" class="btn btn-default">
										<i class="glyphicon glyphicon-edit"></i>
									</a>
								<?php } ?>

								<?php if ($showView) { ?>
									<a href="<?= $model['route'] . '/' . $d->id ?>" type="button"
									   class="btn btn-default">
										<i class="glyphicon glyphicon-eye-open"></i>
									</a>
								<?php } ?>

								<?php if ($showDelete) { ?>
									<a data-method="delete" data-token="<?= csrf_token() ?>"
									   href="<?= route('admin.destroy', array('slug' => $model['route'], 'id' => $d->id), $absolute = true) ?>"
									   type="button" class="btn btn-default">
										<i class="glyphicon glyphicon-remove"></i>
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

//HTML::macro('table_php', function($model, $data = array(), $fields, $options, $showEdit = true, $showDelete = true, $showView = true){
//
//    $table = '<table class="table table-bordered table-striped" data-page="'.Paginator::getCurrentPage().'" data-start="'.$data[0]->sort_id.'" data-sort-url="'.URL::route("admin.sort", array($model['keyname'],'replace_old_id','replace_new_id')).'">';
//
//    $table .='<tr>';
//
//    foreach ($fields as $field)
//    {
//        $table .= '<th>' . Str::title($field->title) . '</th>';
//    }
//
//    if ($showEdit || $showDelete || $showView ) {
//        $table .= '<th></th>';
//    }
//
//    $table .= '</tr><tbody class="sortable">';
//
//    foreach ( $data as $d )
//    {
//        $table .= '<tr data-id="'. $d->id .'"  data-sort-id="'. $d->sort_id .'">';
//
//        foreach($model['columns'] as $key) {
//            $table .= '<td>' . $fields[$key]->show($d->$key) . '</td>';
//        }
//
//        if ($showEdit || $showDelete || $showView )
//        {
//            $table .= '<td>';
//            if ($showEdit)
//                $table .= '<a href="'. route('admin.edit', array('slug' => $model['keyname'], 'id' => $d->id), $absolute = true) .'" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-edit"></i></a> ';
//            if ($showView)
//                $table .= '<a href="' . $model['keyname'] . '/' . $d->id . '" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a> ';
//            if ($showDelete)
//
//
//                $table .= '<a data-method="delete" href="' . route('admin.destroy', array('slug' => $model['keyname'], 'id' => $d->id), $absolute = true) . '" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a> ';
////                    $table .= '<a href="' . route('admin.destroy', array('slug' => $model['keyname'], 'id' => $d->id), $absolute = true) . '" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Delete</a> ';
//            $table .= '</td>';
//        }
//
//        $table .= '</tr>';
//    }
//
//    $table .= '</tbody></table>';
//
//    return $table;
//});

HTML::macro('admin_notifications', function ($notifications) {
	if (!empty($notifications[0])) {
		foreach ($notifications[0] as $notification) {
			?>
			<div
				class="alert alert-<?php print $notification['type']; ?> alert-dismissable admin-notification <?php (!$notification['autohide']) ?: print 'autohide' ?>">
				<i class="fa fa-check"></i>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<?php print $notification['value']; ?>
			</div>
		<?php
		}
	}
});
