<div class="col-sm-{{ $field['cols'] or 6 }}{{ !empty($field['offs']) ? " offset-sm-{$field['offs']}" : '' }}">
    <div class="mt-4 checkbox">
        <label>
            <input type="checkbox" name="{{ $name }}" value="1"> @lang($field['label'])
        </label>
    </div>
</div>