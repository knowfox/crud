@if (!empty($breadcrumbs))
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $link => $title)
            @if ($loop->last)
                <li class="breadcrumb-item active">{{ $title }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{$link}}">{{ $title }}</a></li>
            @endif
        @endforeach
    </ol>
@endif
