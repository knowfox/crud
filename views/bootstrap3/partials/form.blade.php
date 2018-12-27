@php
    $form_attributes = $has_file ? ' id="dropzone" class="dropzone"' : '';
@endphp

@if ($mode == 'create' || !empty($action))
    <form{!! $form_attributes !!} enctype="multipart/form-data" action="{{ !empty($action) ? $action : route($entity_name . '.store') }}" method="{{ !empty($method) ? $method : 'POST' }}">
@else
    <form{!! $form_attributes !!} enctype="multipart/form-data" action="{{ route($entity_name . '.update', [$entity])}}" method="POST">
        <input type="hidden" name="_method" value="PUT">
@endif
    @if (empty($method) || $method != 'GET')
        {{ csrf_field() }}
    @endif

@if ($has_file)
    <div class="row">
        <div class="col dropzone-previews"></div>
    </div>
@endif

    <div class="row">
        <div class="col">
            <div class="row">
                @yield('above-fields')

                @foreach ($fields as $name => $field)
                    @if(is_string($field))
                        @include('crud::fields.text', [
                            'field' => [ 'label' => $field ]
                        ])
                    @else
                        @include('crud::fields.' . (isset($field['type']) ? $field['type'] : 'text'))
                    @endif
                @endforeach

                @yield('below-fields')
            </div>

            <hr>

            @if ($mode == 'create' || !empty($button))
                <button type="submit" class="pull-right btn btn-default">{!! !empty($button) ? $button : ('<i class="glyphicon glyphicon-plus"></i>' . __('Create')) !!}</button>
            @else
                <button type="submit" class="pull-right btn btn-default"><i class="glyphicon glyphicon-save"></i> @lang('Save')</button>
            @endif

            @yield('buttons')

        </div>
    </div>
</form>
