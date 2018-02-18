<div class="col-sm-{{ $field['cols'] or 6 }}{{ !empty($field['offs']) ? " offset-sm-{$field['offs']}" : '' }}">
    <div class="checkbox">
        <label>
            <input type="checkbox" name="{{ $name }}"> {{ $field['label'] }}
        </label>
    </div>
</div>