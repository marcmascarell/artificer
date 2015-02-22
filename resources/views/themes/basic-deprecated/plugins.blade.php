@extends('admin::base')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<h1>plugins here</h1>
		</div>

		@foreach($plugins as $plugin)
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">

						{{ $plugin->name }}


					</div>

					<div class="panel-body">
						{{ $plugin->description }}

						@if (isset($plugin->options))
							<div class="text-center">
								<div class="btn-group">
									@foreach ($plugin->options as $key => $value)
									<a href="{{ route('admin.plugin.page', array('slug' => $plugin->namespace, 'page' => $key), $absolute = true) }}" type="button" class="btn btn-default">
										{{ $value['title'] }}
									</a>
									@endforeach
								</div>
							</div>
						@endif


					</div>

					<table class="table table-bordered">
						<thead>
							<tr>
								<th>
									Version
								</th>

								<th>
									Author
								</th>
							</tr>
						</thead>

						<tbody>
							<tr>
								<td>{{ $plugin->version }}</td>
								<td>{{ $plugin->author }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		@endforeach
	</div>

@stop