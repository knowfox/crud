@if (!empty($breadcrumbs))
    <ul class="uk-breadcrumb uk-margin-top uk-padding-small uk-background-default">
        @foreach ($breadcrumbs as $link => $title)
            @if ($loop->last)
                <li><span>{{ $title }}</span></li>
            @else
                <li><a href="{{ $link }}">{{ $title }}</a></li>
            @endif
        @endforeach
</ul>
@endif
