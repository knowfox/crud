<?php ob_start(); ?>
@if ($info['type'] == 'money')
    {{ number_format($value, 2, ',', '.') }} €
@else
    @if ($info['type'] == 'date')
        {{ strftime('%d.%m.%Y', strtotime($value)) }}
    @else
        @if ($info['type'] == 'image')
            <img src="{{ $entity->getFirstMediaUrl('images', isset($info['style']) ? $info['style'] : 'thumb') }}">
        @else
            @if ($info['type'] == 'tags')
                @foreach ($entity->tagsWithType($col) as $tag)
                    <span class="badge badge-light">{{ $tag->name }}</span>
                @endforeach
            @else
                @if ($info['type'] == 'raw')
                    {!! $value !!}
                @else
                    {{ $value }}
                @endif
            @endif
        @endif
    @endif
@endif
<?php
$rendered_value = ob_get_clean();
if ($i == 0 && $show) {
?><a href="{{ route($entity_name . '.show', $entity) }}">{{ $rendered_value }}</a><?php
}
else {
    echo $rendered_value;
}
?>
