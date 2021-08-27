<div class="col-sm-{{ $field['cols'] ?? 6 }}{{ !empty($field['offs']) ? " offset-sm-{$field['offs']}" : '' }}">
    <div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
        <label for="{{ $name }}">{{ __($field['label']) }}</label>
        <select name="{{ $name }}[]" id="tag-input-{{ $name }}" class="form-control" multiple="multiple">
            @if (!empty($options))
                @foreach ($options as $key => $value)
                    <option{!! !empty($entity) && $entity->{$name} == $key ? ' selected' : '' !!} value="{{ $key }}">{{ $value }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

