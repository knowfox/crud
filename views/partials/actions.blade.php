<a class="btn btn-default" href="{{ route($entity_name . '.edit', $entity) }}">
    <i class="fas fa-pen-square"></i> Bearbeiten
</a>
@if ($deletes)
    <a class="btn btn-default" href="#"
       onclick="event.preventDefault(); if (confirm('Fahrt wirklich löschen?')) document.getElementById('delete-form-{{$entity_id}}').submit();"><i class="glyphicon glyphicon-trash"></i> Löschen</a>

    <form id="delete-form-{{$entity_id}}" action="{{route($entity_name . '.destroy', ['id' => $entity_id])}}" method="POST" style="display: none;">
        <input type="hidden" name="_method" value="DELETE">
        {{ csrf_field() }}
    </form>
@endif
