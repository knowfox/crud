<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
    <div{!! $errors->has($name) ? ' class="uk-form-danger"' : '' !!}>
        <label class="uk-form-label" for="{{ $name }}">{{ __($field['label']) }}</label>

        <?php
        $value = $errors->any() ? old($name) : (isset($entity->{$name}) ? $entity->{$name} : '');
        if ($type == 'date') {
            $value = preg_replace('/ \S+$/', '', $value);
        }
        ?>
        @if (!empty($field['after']))
            <div class="uk-form-controls">
                @include('crud::' . $theme . '.fields._input')
                <span class="input-group-addon">{{ $field['after'] }}</span>
            </div>
        @elseif (!empty($field['button']))
            <div class="uk-form-controls">
                @include('crud::' . $theme . '.fields._input')
                <span class="input-group-btn">{!! preg_replace_callback('/%([^%]+)%/', function ($matches) use ($entity)
                {
                    $field = $matches[1];
                    return $entity->{$field};

                }, $field['button']) !!}</span>
            </div>
        @else
            @include('crud::' . $theme . '.fields._input')
        @endif

    </div>
</div>
