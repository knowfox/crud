@if (count($errors) > 0)
    <div class="alert one alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>@lang($error)</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('error'))
    <div class="alert two alert-danger">
        {!! __(session('error')) !!}
    </div>
@endif
@if (session('status'))
    <div class="alert alert-success">
        {!! __(session('status')) !!}
    </div>
@endif