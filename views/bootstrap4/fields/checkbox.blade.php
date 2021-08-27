<div class="col-sm-{{ $field['cols'] ?? 6 }}{{ !empty($field['offs']) ? " offset-sm-{$field['offs']}" : '' }}">
    <div class="mt-4 checkbox">
        <label>
            <input type="checkbox" name="{{ $name }}" value="1"
                {!! !empty($entity->{$name}) ? 'checked="CHECKED"' : '' !!}> @lang($field['label'])
        </label>
    </div>
</div>