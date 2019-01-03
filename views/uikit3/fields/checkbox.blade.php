<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
        <label>
            <input type="checkbox" name="{{ $name }}" value="1"
                {!! !empty($entity->{$name}) ? 'checked="CHECKED"' : '' !!}> @lang($field['label'])
        </label>
</div>