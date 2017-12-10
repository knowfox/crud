@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-lg-12">

                @if ($has_create)
                    <div class="pull-right mt-4">
                        <a href="{{ $create['route'] }}" class="btn btn-default">
                            <i class="glyphicon glyphicon-plus"></i> {{ __($create['title']) }}
                        </a>
                    </div>
                @endif

                <h1 class="page-header">{!! $page_title !!}</h1>
                @include('crud::partials.breadcrumbs')
                @include('crud::partials.messages')
            </div>
        </div>

        <div class="row">
            <div class="col-sm-offset-1 col-sm-10">
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
                                        ?>
                                        <th>{{ $title }}</th>
                                    @endforeach
                                        <th style="width:10em">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($entities as $entity)
                                    <tr>
                                        @foreach ($cols as $col => $info)
                                            <td>
                                                <?php ob_start(); ?>
                                                @if ($info['type'] == 'money')
                                                    {{ number_format($entity->{$col}, 2, ',', '.') }} €
                                                @else
                                                    @if ($info['type'] == 'date')
                                                        {{ strftime('%d.%m.%Y', strtotime($entity->{$col})) }}
                                                    @else
                                                        {{ $entity->{$col} }}
                                                    @endif
                                                @endif
                                                <?php
                                                    $value = ob_get_clean();
                                                    if ($show) {
                                                        ?><a href="{{ route($entity_name . '.show', $entity) }}">{{ $value }}</a><?php
                                                    }
                                                    else {
                                                        echo $value;
                                                    }
                                                ?>
                                            </td>
                                        @endforeach
                                        <td class="text-right" style="white-space: nowrap">
                                            @if (!isset($entity->readonly) || !$entity->readonly)
                                                @include('crud::partials.actions', ['entity_id' => $entity->id])
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">{{ $entities->links() }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection