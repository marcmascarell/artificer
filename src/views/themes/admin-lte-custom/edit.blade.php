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

	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<!--array('Mascame\Artificer\Controller@update', array('slug' => $model['route'], 'id' => $items->id))-->

			{{ Form::model($items, array(
                'route' => array($form_action_route, $model['route'], $items->id),
                'class' => "NO-form-inline dropzone",
                'id' => 'admin-form',
                'method' => $form_method,
                'files' => true,
                'data-file-upload' => URL::route("admin.upload", array($model["route"], \Mascame\Artificer\Artificer::getCurrentModelId($items)))
                )
			); }}

				@foreach ($fields as $field)
                    @if ( ! $field->isHidden())
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
				    @endif
				@endforeach

				{{ Form::submit('Send', array('class' => "btn btn-default")) }}
			{{ Form::close() }}
		</div>
	</div>

@stop