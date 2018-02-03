<?php ob_start(); ?>
@if ($info['type'] == 'money')
    {{ number_format($value, 2, ',', '.') }} €
@else
    @if ($info['type'] == 'date')
        {{ strftime('%d.%m.%Y', strtotime($value)) }}
    @else
        {{ $value }}
    @endif
@endif
<?php
$rendered_value = ob_get_clean();
if ($i == 0 && $show) {
?><a href="{{ route($entity_name . '.show', $entity) }}">{{ $rendered_value }}</a><?php
}
else {
    echo $value;
}
?>
