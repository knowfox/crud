<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
    <div{!! $errors->has($name) ? ' class="uk-form-danger"' : '' !!}>
        <label class="uk-form-label" for="{{ $name }}">{{ __($field['label']) }}</label>
        <textarea id="{{ $name }}-field" class="uk-textarea" {!! isset($field['rows']) ? "style=\"height:{$field['rows']}em\" " : '' !!}name="{{ $name }}">{!! $entity->{$name} ?? '' !!}</textarea>
    </div>
</div>