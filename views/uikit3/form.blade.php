@extends($layout)

@section('content')
    <div class="uk-container uk-height-viewport uk-padding uk-padding-remove-top" style="background-color: rgba(255,255,255,0.8)">

        @include('crud::' . $theme . '.partials.breadcrumbs')

        <div class="uk-flex uk-flex-between">
            <h1 class="uk-heading-medium uk-heading-bullet">{!! $page_title !!}</h1>
        </div>

        @include('crud::' . $theme . '.partials.messages')

        @include('crud::' . $theme . '.partials.form')
    </div>
@endsection
