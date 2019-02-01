<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
    <div class="{{ $errors->has($name) ? ' uk-form-danger' : '' }}">
        <label class="uk-form-label" for="{{ $name }}">{{ __($field['label']) }}</label>
        <select name="{{ $name }}" id="ref-input-{{ $name }}" data-except="{{ $entity->id ?? '' }}">
            @if (!empty($entity->$name))
                <option value="{{ $entity->$name }}" selected="selected">{{ $field['ref_title']($entity) }}</option>
            @endif
        </select>
    </div>
</div>

@push('scripts')
    <script>
        $('#ref-input-{{ $name }}').selectize({
            persist: false,
            valueField: 'id',
            labelField: '{{ $field['label_field'] ?? 'title' }}',
            searchField: '{{ $field['search_field'] ?? 'title' }}',
            create: false,
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '{{ $field['url'] }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        except: $(this).get(0).$input.data('except'),
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