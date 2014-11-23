@extends('admin.base')

@section('content')
<h2>{{ $model['name'] }}</h2>

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

@stop