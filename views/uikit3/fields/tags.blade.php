<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
    <div class="{{ $errors->has($name) ? ' uk-form-danger' : '' }}">
        <label class="uk-form-label" for="{{ $name }}">{{ __($field['label']) }}</label>
        <select name="{{ $name }}[]" id="tag-input-{{ $name }}" multiple="multiple">
            @if (!empty($options))
                @foreach ($options as $key => $value)
                    <option{!! !empty($entity) && $entity->{$name} == $key ? ' selected' : '' !!} value="{{ $key }}">{{ $value }}</option>
                @endforeach
            @else
                @foreach ($entity->$name as $tag)
                    <option value="{{ $tag->slug }}" selected="selected">{{ $tag->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

@push('scripts')
    <script>
        $('#tag-input-{{ $name }}').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'slug',
            labelField: 'name',
            searchField: 'name',
            create: function(input) {
                return {
                    slug: input,
                    name: input
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '{{ $field['url'] }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        q: query
                    },
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback(res.data);
                    }
                });
            }
        });
    </script>
@endpush