<a class="btn btn-default" href="{{ route($entity_name . '.edit', $entity) }}">
    <i class="fas fa-pen-square"></i> @lang('Edit')
</a>
@if ($deletes)
    <a class="btn btn-default" href="#"
       onclick="event.preventDefault(); if (confirm('Really delete {{ $entity_title }}?')) document.getElementById('delete-form-{{ $entity->id }}').submit();"><i class="fas fa-trash"></i> @lang('Delete')</a>

    <form id="delete-form-{{ $entity->id }}" action="{{route($entity_name . '.destroy', ['id' => $entity->id])}}" method="POST" style="display: none;">
        <input type="hidden" name="_method" value="DELETE">
        {{ csrf_field() }}
    </form>
@endif
