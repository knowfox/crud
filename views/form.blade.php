@extends('layouts.app')

@section('content')
    <div class="container" style="padding-bottom:20px">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{!! $page_title !!}</h1>
            </div>
        </div>

        @include('crud::partials.breadcrumbs')
        @include('crud::partials.messages')

        @include('crud::partials.form')
    </div>
@endsection