@if (isset($entity->field) && $entity->field == 'table')
    @include('crud::fields.table')
@else
    @include('crud::fields.textarea')
@endif