<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
    <div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
        <label class="uk-form-label" for="{{ $name }}">{{ __($field['label']) }}</label>
        <textarea id="{{ $name }}-field" class="uk-textarea" {!! isset($field['rows']) ? "style=\"height:{$field['rows']}em\" " : '' !!}name="{{ $name }}">{!! $entity->{$name} ?? '' !!}</textarea>
    </div>
</div>