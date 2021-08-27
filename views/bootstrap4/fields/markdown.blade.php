<div class="col-sm-{{ $field['cols'] ?? 12 }}">
    <div class="form-group{{ $errors->has($name) ? ' has-error' : '' }}">
        <label for="{{ $name }}">{{ __($field['label']) }}</label>
        <textarea class="form-control" name="{{ $name }}">{!! $entity->{$name} or '' !!}</textarea>
    </div>
</div>

@push('scripts')
    <script>
        new InscrybMDE({
            element: $("textarea[name={{ $name }}]")[0],
            autofocus: true,
            autosave: {
                enabled: true,
                uniqueId: location.href.replace('/', '-') + '-{{ $name }}'
            },
            spellChecker: false,
            toolbar: false /*[
                {
                    name: "bold",
                    action: InscrybMDE.toggleBold,
                    className: "fa fa-bold fa-fw",
                    title: "Bold"
                },
                {
                    name: "italic",
                    action: InscrybMDE.toggleItalic,
                    className: "fa fa-italic fa-fw",
                    title: "Italic"
                },
                {
                    name: "heading-2",
                    action: InscrybMDE.toggleHeading2,
                    className: "fa fa-header fa-header-x fa-header-2 fa-fw",
                    title: "Medium Heading"
                },
                "|",
                {
                    name: "quote",
                    action: InscrybMDE.toggleBlockquote,
                    className: "fa fa-quote-left fa-fw",
                    title: "Blockquote"
                },
                {
                    name: "unordered-list",
                    action: InscrybMDE.toggleUnorderedList,
                    className: "fa fa-list-ul fa-fw",
                    title: "Generic List"
                },
                {
                    name: "ordered-list",
                    action: InscrybMDE.toggleOrderedList,
                    className: "fa fa-list-ol fa-fw",
                    title: "Numbered List"
                },
                "|",
                {
                    name: "link",
                    action: InscrybMDE.drawLink,
                    className: "fa fa-link fa-fw",
                    title: "Create Link"
                },
                {
                    name: "image",
                    action: InscrybMDE.drawImage,
                    className: "fa fa-picture-o fa-fw",
                    title: "Insert Image"
                },
                {
                    name: "table",
                    action: InscrybMDE.drawTable,
                    className: "fa fa-table fa-fw",
                    title: "Insert Table"
                }
            ]*/
        });

    </script>
@endpush