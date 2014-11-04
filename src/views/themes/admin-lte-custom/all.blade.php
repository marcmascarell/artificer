@extends($layout)

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
	<?php Event::fire('artificer.before.list', $items, $halt = false); ?>

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('admin.model.create', $model['route']) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> New
            </a>
        </div>
    </div>


    @if (!$items->isEmpty())
        <div class="row">
            <div class="col-md-offset-8 col-md-4">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">
                        <i class="fa fa-filter"></i> Filter
                        </h3>
                    </div>
                    <div class="box-body">
                        {{ Form::open(array('route' => array('admin.model.filter', $model['route']), 'method' => 'post')) }}
                            @foreach($fields as $field)
                                @if ($field->hasFilter())
                                    {{ Str::title($field->title) }}
                                @endif
                                {{ $field->displayFilter() }}
                            @endforeach

                            <br>

                            <div class="text-right">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>

                        {{ Form::close() }}
                    </div>

                </div>

            </div>
        </div>

        {{ HTML::table($model, $items, $fields, $models[$model['name']]['options'], $sort,
        $permit['view'],
        $permit['update'],
        $permit['delete'])
        }}

        {{ Form::open(array('route' => array('admin.model.sort', $model['route'], '', ''))) }}
            {{ Form::submit('Submit!', array('class' => 'hidden', 'id' => 'sort-submit')); }}
        {{ Form::close() }}
    @else
        No results
    @endif

	<?php Event::fire('artificer.after.list', $items, $halt = false);  ?>
@stop

