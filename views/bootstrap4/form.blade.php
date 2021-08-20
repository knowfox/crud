@extends($layout)

@section('content')
    <div class="container" style="padding-bottom:20px">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{!! $page_title !!}</h1>
            </div>
        </div>

        @include('crud::' . $theme . '.partials.breadcrumbs')
        @include('crud::' . $theme . '.partials.messages')

        @include('crud::' . $theme . '.partials.form')
    </div>
@endsection