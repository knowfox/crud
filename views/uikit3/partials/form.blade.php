@php
    $method_override = null;
    if ($mode == 'create' || !empty($action)) {
        $action = $action ?? route($route_prefix . $entity_name . '.store');
        $method = $method ?? 'POST';
    }
    else {
        $action = route($route_prefix . $entity_name . '.update', $entity);
        $method = 'POST';
        $method_override = true;
    }
@endphp

@yield('above-fields')

@if ($mode != 'create' && $has_file)
    <form id="dropzone" class="dropzone uk-margin" enctype="multipart/form-data" action="{{ $action }}" method="{{ $method }}">
        @if ($method_override)
            <input type="hidden" name="_method" value="PUT">
        @endif
        @if (empty($method) || $method != 'GET')
            {{ csrf_field() }}
        @endif
        <div class="dropzone-previews"></div>
        <div id="images" class="uk-grid-small" uk-grid uk-sortable="handle: .uk-sortable-handle"></div>
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
    @if ($mode != 'create' && $has_file)
        <script>
            $.get('{{ $images_path }}', function (images) {
                $.each(images.data, function (i, image) {
                    $('#images').append(`
                    <div data-id="${image.id}">
                        <figure class="uk-card uk-card-default uk-card-body">
                            <div class="uk-sortable-handle uk-position-center-left uk-padding-small uk-background-default" uk-icon="menu"></div>
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

            UIkit.util.on('#images', 'moved', function () {
                var $images = document.getElementsByClassName('uk-sortable-item'),
                    images = Array.from($images)
                        .map(function (img) { return img.dataset.id; });

                console.log(images);
                
                axios.post('/admin/media/sort', { images })
                    .then(function () {
                        console.log('Sort order saved');
                    });
            });
        </script>
    @endif
@endpush
