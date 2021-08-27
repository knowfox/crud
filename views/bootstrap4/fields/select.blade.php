<div class="col-sm-{{ $field['cols'] ?? 6 }}{{ !empty($field['offs']) ? " offset-sm-{$field['offs']}" : '' }}">
    <div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
        <label for="{{ $name }}">{{ __($field['label']) }}</label>
        <select name="{{ $name }}" class="form-control">
            @if (!empty($options))
                @foreach ($options as $key => $value)
                    <option{!! !empty($entity) && $entity->{$name} == $key ? ' selected' : '' !!} value="{{ $key }}">{{ $value }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

@push('scripts')
    <script>
        var {{ $name }}_options = {!! isset($options) ? json_encode($options) : 'null' !!};
            {{ $name  }}_selected = "{{ !empty($entity) ? htmlentities($entity->{$name}) : '' }}";
    </script>
@endpush