@if ($mode == 'create' || !empty($action))
    <form enctype="multipart/form-data" action="{{ !empty($action) ? $action : route($entity_name . '.store') }}" method="{{ !empty($method) ? $method : 'POST' }}">
@else
    <form enctype="multipart/form-data" action="{{ route($entity_name . '.update', [$entity])}}" method="POST">
        <input type="hidden" name="_method" value="PUT">
@endif
    @if (empty($method) || $method != 'GET')
        {{ csrf_field() }}
    @endif

    <div class="row">
        <div class="col-sm-offset-1 col-sm-10">
            <div class="row">
                @yield('context')

                @foreach ($fields as $name => $field)
                    @if(is_string($field))
                        @include('crud::fields.text', [
                            'field' => [ 'label' => $field ]
                        ])
                    @else
                        @include('crud::fields.' . (isset($field['type']) ? $field['type'] : 'text'))
                    @endif
                @endforeach
            </div>

            <hr>

            @if ($mode == 'create' || !empty($button))
                <button type="submit" class="pull-right btn btn-default">{!! !empty($button) ? $button : '<i class="glyphicon glyphicon-plus"></i> Anlegen' !!}</button>
            @else
                <button type="submit" class="pull-right btn btn-default"><i class="glyphicon glyphicon-save"></i> Speichern</button>
            @endif

            @yield('buttons')

        </div>
    </div>
</form>
