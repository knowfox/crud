<div class="col-sm-{{ $field['cols'] or 6 }}{{ !empty($field['offs']) ? " offset-sm-{$field['offs']}" : '' }}">
    <div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
        <label for="{{ $name }}">{{ __($field['label']) }}</label>

        <?php
        $value = $errors->any() ? old($name) : (isset($entity->{$name}) ? $entity->{$name} : '');
        ?>
        @if (!empty($field['after']))
            <div class="input-group">
                @include('crud::fields._input')
                <span class="input-group-addon">{{ $field['after'] }}</span>
            </div>
        @elseif (!empty($field['button']))
            <div class="input-group">
                @include('crud::fields._input')
                <span class="input-group-btn">{!! preg_replace_callback('/%([^%]+)%/', function ($matches) use ($entity)
                {
                    $field = $matches[1];
                    return $entity->{$field};

                }, $field['button']) !!}</span>
            </div>
        @else
            @include('crud::fields._input')
        @endif

    </div>
</div>
