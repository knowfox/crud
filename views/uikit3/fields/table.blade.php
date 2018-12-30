<div class="col-sm-{{ $field['cols'] or 12 }}">
    <label>Werte</label>
    <ul class="list-group" id="items"></ul>
    <button class="btn btn-default" id="add-item"><i class="fas fa-plus-square"></i> @lang('New entry')</button>
    <input type="hidden" id="field-value" name="{{ $name }}" value="{{$entity->{$name} or ''}}">
</div>

@push('scripts')
<script>
    var list = {!! $entity->value !!},
        el, sortable;

    function save(sortable) {
        $('#field-value').val(JSON.stringify(sortable.toArray()));
    }

    function item(value) {
        var el = document.createElement('li');
        el.className = 'list-group-item';
        el.dataset.id = value;
        el.innerHTML = value + ' <span class="js-remove"><i class="fas fa-minus-square"></i></span>';
        return el;
    }

    $(list).each(function () {
        $('#items').append(item(this));
    });

    el = document.getElementById('items');
    sortable = Sortable.create(el, {
        animation: 150,
        filter: '.js-remove',
        onFilter: function (e) {
            e.item.parentNode.removeChild(e.item);
            save(sortable);
        },
        store: {
            get: function (sortable) {
                return [];
            },
            set: function (sortable) {
                save(sortable);
            }
        }
    });

    document.getElementById('add-item').onclick = function (e) {
        e.preventDefault();

        Ply.dialog('prompt', {
            title: '{{ __('New entry') }}',
            form: { name: '{{ __('Value') }}' }
        }).done(function (ui) {
            sortable.el.appendChild(item(ui.data.name));
            save(sortable);
        });
    };

</script>
@endpush

@push('styles')
    <style>
        .js-remove {
            margin-left: 10px;
        }
        #items {
            list-style-type: none;
            margin: 0 0 20px 0;
            padding: 0;
        }
        #items .js-handle {
            cursor: move;
        }
        .ply-ok {
            background-color: #337ab7;
            border-color: #2e6da4;
        }
        .ply-cancel {
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }
        .ply-input:focus {
            border-color: #ccc;
        }
        #items li {
            position: relative;
        }
        #items li .js-remove {
            position: absolute;
            top: 1em;
            right: 10px;
        }
    </style>
@endpush