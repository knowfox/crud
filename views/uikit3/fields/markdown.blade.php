<div class="uk-width-{{ $field['width'] ?? '1-1' }}@m">
    <div{!! $errors->has($name) ? ' class="uk-form-danger"' : '' !!}>
        <label class="uk-form-label" for="{{ $name }}">{{ __($field['label']) }}</label>
        <textarea class="uk-textarea" name="{{ $name }}">{!! $entity->{$name} ?? '' !!}</textarea>
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
            toolbar: [
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
            ]
        });

    </script>
@endpush