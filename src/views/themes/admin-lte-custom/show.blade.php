@extends($theme . '.base')

@section('content')
<h2>{{ $model['name'] }}</h2>

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('admin.edit', array($model['keyname'], $fields['id']->value)) }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <ul class="list-group">
                <li class="list-group-item">
                @foreach ($fields as $field)


                    <ul class="list-group">
                        <li class="list-group-item">
                            ({{ $field->type }})
                        </li>

                        <li class="list-group-item">
                            <strong>
                                {{ $field->name }}
                            </strong>
                        </li>

                        <li class="list-group-item">
                            {{ $field->show() }}
                        </li>
                    </ul>

                @endforeach
                </li>
            </ul>
        </div>
    </div>

@stop