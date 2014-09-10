@extends('admin::base')

@section('assets')

@stop

@section('content')
<h2>{{ $model['name'] }}</h2>

	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<!--array('Mascame\Artificer\Controller@update', array('slug' => $model['keyname'], 'id' => $items->id))-->

			{{ Form::model($items, array('route' => array('admin.update', $model['keyname'], $items->id), 'class' => "NO-form-inline dropzone", 'id' => 'admin-form', 'method' => 'PUT', 'files' => true, 'data-file-upload' => URL::route("admin.upload", array($model["keyname"], \Mascame\Artificer\Artificer::getCurrentModelId($items))))) }}

				@foreach ($fields as $field)
				<div class="form-group">
					({{ $field->type }})

					{{ Form::label($field->title) }}

					@if($errors->has())
					@foreach ($errors->get($field->name) as $message)
					{{ $message }}
					@endforeach
					@endif

					{{ $field->output() }}
				</div>
				@endforeach

				{{ Form::submit('Send', array('class' => "btn btn-default")) }}
			{{ Form::close() }}
		</div>
	</div>


@stop