@extends($layout)

@section('content')
    <div class="container">

        <div class="row">
            <div class="col">
                <h1 class="page-header">{!! $page_title !!}</h1>
            </div>
            @if ($has_create)
                <div class="col-auto">
                    <a href="{{ $create['route'] }}" class="btn btn-default">
                        <i class="fas fa-plus-square"></i> @lang($create['title'])
                    </a>
                </div>
            @endif
        </div>

        @include('crud::' . $theme . '.partials.breadcrumbs')
        @include('crud::' . $theme . '.partials.messages')

        <div class="row justify-content-center">
            <div class="col-10">
                <div class="row">
                    @yield('context')

                    <div class="col-sm-12">
                        @if ($entities->count() == 0)
                            <p>- {{ $no_result }} -</p>
                        @else
                            <?php
                            $cols = [];
                             ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                    @foreach ($columns as $key => $column)
                                        <?php
                                        $title = is_string($column) ? $column : $column['title'];
                                        $cols[$key] = [
                                            'title' => $title,
                                            'type' => is_string($column) ? 'text' : $column['type'],
                                        ];
                                        if (is_array($column)) {
                                            $cols[$key] += $column;
                                        }
                                        ?>
                                        <th>@lang($title)</th>
                                    @endforeach
                                        <th style="width:10em">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($entities as $entity)
                                    <tr>
                                        <?php $i = 0; ?>
                                        @foreach ($cols as $col => $info)
                                            <td>
                                                @if (strpos($col, '.') !== false)
                                                    <?php
                                                    $scoped_col = preg_split('/\./', $col);
                                                    if ($entity->{$scoped_col[0]}) {
                                                        $value = $entity->{$scoped_col[0]}->{$scoped_col[1]};
                                                    }
                                                    else {
                                                        $value = '--';
                                                    }
                                                    ?>
                                                    @include('crud::' . $theme . '.partials.row', ['value' => $value])
                                                @else
                                                    @include('crud::' . $theme . '.partials.row', ['value' => $entity->{$col}])
                                                @endif
                                                <?php $i++; ?>
                                            </td>
                                        @endforeach
                                        <td class="text-right">
                                            @if (!Auth::guest() && !isset($entity->readonly) || !$entity->readonly)
                                                @include('crud::' . $theme . '.partials.actions', ['entity' => $entity])
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">{{ $entities->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('search_form')
    <div class="justify-content-center">
        <form class="form-inline my-2 my-lg-0" action="{{ route($entity_name . '.index') }}">
            <div class="input-group">
                <input name="q" type="text" class="search-input form-control"
                       placeholder="{{ $search_placeholder or '' }}"
                       value="{{ session('search_term') }}">
                <span class="search-clear"><i class="fa fa-times"></i></span>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $('.search-input').keyup(function() {
            $('.search-clear').toggle(Boolean($(this).val()));
        });
        $('.search-clear').toggle(Boolean($(".search-input").val()));
        $('.search-clear').click(function() {
            $('.search-input').val('').focus();
            $(this).parents('form').submit();
        });
    </script>
@endpush