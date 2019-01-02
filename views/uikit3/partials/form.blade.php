@php
    $form_attributes = $has_file ? ' id="dropzone" class="dropzone uk-form-stacked' : ' class="uk-form-stacked"';
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
    <div uk-grid>
        <div class="col dropzone-previews"></div>
    </div>
@endif

    <div>
        <div uk-grid>
            @yield('above-fields')

            @foreach ($fields as $name => $field)
                @if(is_string($field))
                    @include('crud::' . $theme . '.fields.text', [
                        'field' => [ 'label' => $field ]
                    ])
                @else
                    @include('crud::' . $theme . '.fields.' . (isset($field['type']) ? $field['type'] : 'text'))
                @endif
            @endforeach

            @yield('below-fields')
        </div>

        <hr>

        @if ($mode == 'create' || !empty($button))
            <button type="submit" class="uk-float-right uk-button uk-button-default">{!! !empty($button) ? $button : ('<i class="glyphicon glyphicon-plus"></i>' . __('Create')) !!}</button>
        @else
            <button type="submit" class="uk-float-right uk-button uk-button-default"><i class="glyphicon glyphicon-save"></i> @lang('Save')</button>
        @endif

        @yield('buttons')

    </div>
</form>
