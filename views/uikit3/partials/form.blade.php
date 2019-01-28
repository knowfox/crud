@php
    $method_override = null;
    if ($mode == 'create' || !empty($action)) {
        $action = $action ?? route($route_prefix . $entity_name . '.store');
        $method = $method ?? 'POST';
    }
    else {
        $action = route($route_prefix . $entity_name . '.update', [$entity]);
        $method = 'POST';
        $method_override = true;
    }
@endphp

@yield('above-fields')

@if ($has_file)
    <form id="dropzone" class="dropzone uk-margin" enctype="multipart/form-data" action="{{ $action }}" method="{{ $method }}">
        @if ($method_override)
            <input type="hidden" name="_method" value="PUT">
        @endif
        @if (empty($method) || $method != 'GET')
            {{ csrf_field() }}
        @endif
        <div class="dropzone-previews"></div>
        <div id="images" class="uk-grid-small" uk-grid></div>
    </form>
@endif

<form class="uk-form-stacked" action="{{ $action }}" method="{{ $method }}">
    @if ($method_override)
        <input type="hidden" name="_method" value="PUT">
    @endif
    @if (empty($method) || $method != 'GET')
        {{ csrf_field() }}
    @endif


    <div uk-grid>
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
        <button type="submit" class="uk-float-right uk-button uk-button-primary">{!! !empty($button) ? $button : ('<i class="glyphicon glyphicon-plus"></i>' . __('Create')) !!}</button>
    @else
        <button type="submit" class="uk-float-right uk-button uk-button-primary"><i class="glyphicon glyphicon-save"></i> @lang('Save')</button>
    @endif

    @yield('buttons')
</form>

@push('scripts')
    <script>
        $.get('{{ $images_path }}', function (images) {
            $.each(images.data, function (i, image) {
                $('#images').append(`
                <div>
                    <figure class="uk-card uk-card-default uk-card-body">
                        <a href="#" class="js-remove uk-position-center-right uk-padding-small uk-background-default" data-id="${image.id}" uk-icon="trash"></a>
                        <img src="${image.thumb}">
                        <figcaption class="uk-text-center">${image.name}</figcaption>
                    </figure>
                </div>`);
            });
        });

        $('#images').on('click', '.js-remove', function (e) {
            var $figure = $(this).parent();
            axios.post('/admin/media/' + $(this).data('id') + '/delete')
                .then(function () {
                    $figure.fadeOut();
                });
        });
    </script>
@endpush
