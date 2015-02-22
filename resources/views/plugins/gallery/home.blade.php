@extends($theme . '.base')

@section('content')
<h2>Gallery plugin</h2>

	@foreach ($layouts as $layout_keyname => $layout)
		{{ $layout_keyname }}

		{{ var_dump($layout) }}
	@endforeach

@stop