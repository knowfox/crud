<div style="display:inline-block;text-align:left;">
    <a class="uk-button uk-button-small" uk-icon="pencil" title="@lang('Edit #:id', ['id' => $entity->id])" href="{{ route($route_prefix . $entity_name . '.edit', $entity) }}"></a>
    @if ($downloads)
        <a class="uk-button uk-button-small" uk-icon="download" title="@lang('Download')" href="{{ route($route_prefix . $entity_name . '.download', $entity) }}"></a>
    @endif
    @if ($deletes)
        <a class="uk-button uk-button-small" uk-icon="trash" title="@lang('Delete #:id', ['id' => $entity->id])" href="#"
           onclick="event.preventDefault(); if (confirm('Really delete {{ $entity_title }}?')) document.getElementById('delete-form-{{ $entity->id }}').submit();"></a>

        <form id="delete-form-{{ $entity->id }}" action="{{route($route_prefix . $entity_name . '.destroy', $entity)}}" method="POST" style="display: none;">
            <input type="hidden" name="_method" value="DELETE">
            {{ csrf_field() }}
        </form>
    @endif
</div>
