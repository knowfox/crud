<?php
/**
 * This file is part of Knowfox CRUD, a CRUD package for Laravel
 * Copyright (C) 2017 Olav Schettler <olav@schettler.net>
 *
 * This source code is subject to the terms of the GNU
 * LESSER GENERAL PUBLIC LICENSE Version 3.
 * If a copy of the LGPLv3 was not distributed
 * with this file, You can obtain one at https://opensource.org/licenses/LGPL-3.0
 */
namespace Knowfox\Crud\Services;

use App\Models\Idea;
use App\Models\Inventor;
use Illuminate\Database\Query\Builder;
use Knowfox\Crud\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest as Request;

class Crud
{
    protected $setup;
    protected $setting;
    protected $context = null;

    public function setup($options)
    {
        if (null !== config($options . '.extends')) {
            $parent = config($options . '.extends');
            $prefix = substr($options, 0, strrpos($options, '.') + 1);

            $this->setup = (object)config($prefix . $parent);
        }
        $this->options(config($options));
    }

    public function option($name, $value)
    {
        $this->setup->{$name} = $value;
    }

    public function options($options)
    {
        $this->setup = (object)array_merge_recursive((array)$this->setup, $options);
    }

    private function stripPrefix($text)
    {
        return preg_replace('/^\S* /', '', $text);
    }

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
        $this->setting->upgradeSchema();
    }

    private function viewName($suffix = '')
    {
        $view_name = '';

        $package = config('crud.package');
        if (!empty($package)) {
            $view_name .= $package . '::';
        }

        $view_name .= config('crud.theme') . '.';

        $view_name .= $this->setup->entity_name;

        if ($suffix) {
            $view_name .= '.' . $suffix;
        }
        return $view_name;
    }

    private function listUrl()
    {
        $route_prefix = $this->setup->route_prefix ?? '';
        return isset($this->setup->list_route)
            ? (count($this->setup->list_route) > 1
                ? route($route_prefix . $this->setup->list_route[0], $this->setup->list_route[1])
                : route($route_prefix . $this->setup->list_route[0]))
            : route($route_prefix . $this->setup->entity_name . '.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request $request, $entities = null)
    {
        assert(isset($this->setup->model), 'model is set');
        assert(isset($this->setup->order_by), 'order_by is set on '. $this->setup->model);
        assert(isset($this->setup->entity_name), 'entity_name is set on ' . $this->setup->model);
        assert(isset($this->setup->entity_title), 'entity_title is set on ' . $this->setup->model);

        if (!$entities) {
            $entities = (new $this->setup->model)->newQuery();
        }

        if (is_array($this->setup->order_by)) {
            $order_by = $this->setup->order_by;
        }
        else {
            $order_by = [$this->setup->order_by];
        }
        foreach ($order_by as $order_by_column) {
            $name_and_direction = preg_split('/\|/', $order_by_column);
            if (count($name_and_direction) > 1) {
                $entities->orderBy($name_and_direction[0], $name_and_direction[1]);
            }
            else {
                $entities->orderBy($name_and_direction[0], 'asc');
            }
        }

        if (isset($this->setup->filter) && is_callable($this->setup->filter)) {
            call_user_func($this->setup->filter, $entities);
        }

        if (isset($this->setup->search)) {
            $q = null;
            if ($request->has('q')) {
                $q = $request->q;
                session(['search_term' => $q]);
            }
            else {
                $q = session('search_term', null);
            }
            if ($q) {
                $entities->where($this->setup->search, 'like', '%' . $q . '%');
            }
        }

        $columns = $this->setup->columns;

        if (!empty($this->setup->with)) {
            $entities->with($this->setup->with);
        }

        if ($request->format() == 'json') {
            return $entities->paginate();
        }

        $breadcrumbs = [
            route('home') => __('Start'),
        ];
        if (!empty($this->setup->is_admin) && $this->setup->is_admin) {
            $breadcrumbs['#'] = __('Manage');
        }

        $page_title = __($this->setup->entity_title[1]);
        $breadcrumbs['#index'] = $page_title;

        $has_create = !isset($this->setup->has_create) || $this->setup->has_create;

        $entity_title = __(preg_replace('/^\s*/', '', $this->setup->entity_title[0]));

        $route_prefix = $this->setup->route_prefix ?? '';

        return view($this->viewName('index'), [
            'layout' => isset($this->setup->layout) 
                ? $this->setup->layout 
                : 'layouts.app',
            'theme' => config('crud.theme'),
            'route_prefix' => $route_prefix,
            'page_title' => $page_title,
            'entity_name' => $this->setup->entity_name,
            'has_create' => $has_create,
            'entity_title' => $entity_title,
            'create' => [
                'route' => route($route_prefix . $this->setup->entity_name . '.create'),
                'title' => __('New:entity_title', ['entity_title' => $this->setup->entity_title[0]]),
            ],
            'deletes' => !empty($this->setup->deletes) && $this->setup->deletes,
            'downloads' => !empty($this->setup->downloads) && $this->setup->downloads,
            'no_result' => __('No ' . $this->setup->entity_title[1]),
            'columns' => $this->setup->columns,
            'entities' => $entities->paginate(),
            'context' => $this->context,
            'breadcrumbs' => $breadcrumbs,
            'show' => !empty($this->setup->show) && $this->setup->show,
            'search_placeholder' => __('Search in ' . $this->setup->entity_title[1])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($entity = null)
    {
        $breadcrumbs = [
            route('home') => __('Start'),
        ];
        if (!empty($this->setup->is_admin) && $this->setup->is_admin) {
            $breadcrumbs['#'] = __('Manage');
        }

        $breadcrumbs[$this->listUrl()] = __($this->setup->entity_title[1]);

        $page_title = __('New:entity_title', ['entity_title' => $this->setup->entity_title[0]]);
        $breadcrumbs['#create'] = $page_title;

        return view($this->viewName('create'), [
            'theme' => config('crud.theme'),
            'layout' => isset($this->setup->layout) ? $this->setup->layout : 'layouts.app',
            'page_title' => $page_title,
            'entity_name' => $this->setup->entity_name,
            'model' => $this->setup->model,
            'fields' => $this->setup->fields,
            'entity' => $entity,
            'breadcrumbs' => $breadcrumbs,
            'has_file' => !empty($this->setup->has_file) && $this->setup->has_file,
        ]);
    }

    private function deferredAssign(&$name, $value)
    {
        if (is_callable($value)) {
            $name = $value();
        }
        else {
            $name = $value;
        }
    }

    protected function withDefaults($input)
    {
        foreach ($this->setup->fields as $key => $info) {
            if (isset($this->setup->fields[$key]['force'])) {
                $this->deferredAssign($input[$key], $this->setup->fields[$key]['force']);
            }
            else
            if (empty($input[$key]) && isset($this->setup->fields[$key]['default'])) {
                $this->deferredAssign($input[$key], $this->setup->fields[$key]['default']);
            }
        }
        return $input;
    }

    private function saveFile($request, $entity)
    {
        if ($request->hasFile('file')) {
            $file = $request->file;
            if (!$file->isValid()) {
                return __('Could not upload file');
            }
            else
            if (strpos($file->getMimeType(), 'image/') !== 0) {
                return __('Uploaded file is not an image');
            }

            if (!isset($this->setup->multiple_files) || !$this->setup->multiple_files) {
                $entity->clearMediaCollection('images');
            }

            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $entity->addMedia($file->path())
                ->usingName($filename)
                ->usingFileName($filename)
                ->sanitizingFileName(function($filename) {
                    return str_slug($filename);
                })
                ->toMediaCollection('images');
        }
        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request, $persist = true)
    {
        if ($persist) {
            $model = $this->setup->model::create($this->withDefaults($request->all()));
        }
        else {
            $model = (new $model_name)
                ->fill($this->withDefaults($request->all()));
        }

        $error = $this->saveFile($request, $model);
        if ($error) {
            return [
                $model,
                response()->redirectTo($this->listUrl())
                    ->with('error', $error)
            ];
        }

        if ($request->format() == 'json') {
            return [$model, $model];
        }

        return [
            $model,
            response()->redirectTo($this->listUrl())
                ->with('status', __('New :entity_title created', [
                    'entity_title' => $this->setup->entity_title[0]
                ]))
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $entity, $options = [])
    {
        $page_title = __(
            (!empty($options['verb']) ? $options['verb'] : 'Edit')
            . ' ' . $this->stripPrefix($this->setup->entity_title[0])
        ) . ' #' . $entity->id;

        if (isset($options['breadcrumbs'])) {
            $breadcrumbs = $options['breadcrumbs'];
        }
        else {
            $breadcrumbs = [
                route('home') => __('Start'),
            ];
            if (!empty($this->setup->is_admin) && $this->setup->is_admin) {
                $breadcrumbs['#'] = __('Manage');
            }

            $breadcrumbs[$this->listUrl()] = __($this->setup->entity_title[1]);
            $breadcrumbs['#edit'] = $page_title;
        }

        return view($this->viewName('edit'), [
            'theme' => config('crud.theme'),
            'layout' => isset($this->setup->layout) ? $this->setup->layout : 'layouts.app',
            'page_title' => $page_title,
            'entity_name' => $this->setup->entity_name,
            'fields' => $this->setup->fields,
            'entity' => $entity,
            'action' => !empty($options['action']) ? $options['action'] : null,
            'button' => !empty($options['button']) ? $options['button'] : null,
            'breadcrumbs' => $breadcrumbs,
            'bottom' => !empty($options['bottom']) ? $options['bottom'] : null,
            'has_file' => !empty($this->setup->has_file) && $this->setup->has_file,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $entity)
    {
        $entity->update(
            $this->withDefaults($request->all())
        );

        $error = $this->saveFile($request, $entity);
        if ($error) {
            return response()->redirectTo($this->listUrl())
                ->with('error', $error);
        }

        if ($request->format() == 'json') {
            return $entity;
        }

        return response()->redirectTo($this->listUrl())
            ->with('status', __('Changes to :entity_title saved', [
                    'entity_title' => $this->stripPrefix($this->setup->entity_title[0])
                ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $entity)
    {
        $entity->delete();
        return response()->redirectTo($this->listUrl())
            ->with('status', __($this->stripPrefix($this->setup->entity_title[0]) . ' deleted'));
    }
}
