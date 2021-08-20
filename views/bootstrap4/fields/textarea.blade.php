<div class="col-sm-{{ $field['cols'] or 12 }}">
    <div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
        <label for="{{ $name }}">{{ __($field['label']) }}</label>
        <textarea id="{{ $name }}-field" class="form-control" {!! isset($field['rows']) ? "style=\"height:{$field['rows']}em\" " : '' !!}name="{{ $name }}">{{ $entity->{$name} or '' }}</textarea>
    </div>
</div>
