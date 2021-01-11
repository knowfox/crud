<?php
$value = !empty($entity->{$name}) ? $entity->{$name} : '';
$type = is_numeric($value) ? 'number' : 'text';
?>

@include('crud::' . $theme . '.fields.input', [
    'type' => $type,
    'attr' => 'readonly ',
])
