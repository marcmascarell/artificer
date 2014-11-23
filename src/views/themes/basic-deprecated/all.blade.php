@extends('admin::base')

@section('content')
<h2>{{ $model['name'] }}</h2>

<?php //echo $items->appends(Input::except('page'))->links(); ?>
<?php Event::fire('artificer.view.all.before.showList', $items, $halt = false);  ?>
@include('admin::partials.before_list')


{{ HTML::table($model, $items, $fields, $models[$model['name']]['options'], $sort) }}

{{ Form::open(array('route' => array('admin.model.sort', $model['keyname'], '', ''))) }}
    {{ Form::submit('Submit!', array('class' => 'hidden', 'id' => 'sort-submit')); }}
{{ Form::close() }}

@include('admin::partials.after_list')
<?php Event::fire('artificer.view.all.after.showList', $items, $halt = false);  ?>


@stop