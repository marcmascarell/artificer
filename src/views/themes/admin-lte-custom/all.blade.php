@extends($theme . '.base')

@section('content-header')
	<h1>
		{{ $model['name'] }}
		<small>Model</small>
	</h1>
	<ol class="breadcrumb">
		<li>
			<a href="#">
				<i class="fa fa-dashboard"></i> Dashboard
			</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-th"></i> Models
			</a>
		</li>
		<li class="active">{{ $model['name'] }}</li>
	</ol>
@overwrite

@section('content')
	<?php Event::fire('before-list', $items, $halt = false); ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('admin.create', $model['route']) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> New
            </a>
        </div>
    </div>

	{{ HTML::table($model, $items, $fields, $models[$model['name']]['options'], $sort) }}

	{{ Form::open(array('route' => array('admin.sort', $model['route'], '', ''))) }}
		{{ Form::submit('Submit!', array('class' => 'hidden', 'id' => 'sort-submit')); }}
	{{ Form::close() }}

	<?php Event::fire('after-list', $items, $halt = false);  ?>
@stop

