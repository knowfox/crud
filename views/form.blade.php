@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding-bottom:20px">
        @include('crud::partials.messages')
        @include('crud::partials.breadcrumbs')

        <div class="page-header">
            <h1>{{$page_title}}</h1>
        </div>

        @include('crud::partials.form')
    </div>
@endsection