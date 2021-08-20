@if (isset($entity->field) && $entity->field == 'table')
    @include('crud::' . $theme . '.fields.table')
@else
    @include('crud::' . $theme . '.fields.textarea')
@endif