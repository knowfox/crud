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
namespace Knowfox\Crud\Controllers;

use App\Models\Idea;
use App\Models\Inventor;
use Knowfox\Crud\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Support\Facades\Artisan;

class CrudController extends Controller
{
    protected $setup;
    protected $context = null;

    private function stripPrefix($text)
    {
        return preg_replace('/^\S* /', '', $text);
    }

    public function __construct()
    {
        parent::__construct();

        $app_version = config('app.version');
        $schema_version = Setting::where('name', 'version')->pluck('value')->first();

        if (!$schema_version || version_compare($app_version, $schema_version) > 0) {
            Artisan::call('migrate', ['--force' => true]);
            Setting::updateOrCreate([
                'name' => 'version'
            ], [
                'value' => $app_version,
                'field' => 'simple',
            ]);
        }
    }

    private function viewName($suffix = '')
    {
        $view_name = '';
        if (!empty($this->setup->package_name)) {
            $view_name .= $this->setup->package_name . '::';
        }
        $view_name .= $this->setup->entity_name;

        if ($suffix) {
            $view_name .= '.' . $suffix;
        }
        return $view_name;
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $order_by = preg_split('/\|/', $this->setup->order_by);
        if (count($order_by) > 1) {
            $entities = $this->setup->model::orderBy($order_by[0], $order_by[1]);
        }
        else {
            $entities = $this->setup->model::orderBy($order_by[0], 'asc');
        }

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

        return view($this->viewName('index'), [
            'page_title' => $page_title,
            'entity_name' => $this->setup->entity_name,
            'has_create' => $has_create,
            'create' => [
                'route' => route($this->setup->entity_name . '.create'),
                'title' => __('New :entity_title', ['entity_title' => $this->setup->entity_title[0]]),
            ],
            'deletes' => !empty($this->setup->deletes) && $this->setup->deletes,
            'no_result' => 'Keine ' . $this->setup->entity_title[1],
            'columns' => $this->setup->columns,
            'entities' => $entities->paginate(),
            'context' => $this->context,
            'breadcrumbs' => $breadcrumbs,
            'show' => !empty($this->setup->show) && $this->setup->show,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCrud($entity = null)
    {
        $breadcrumbs = [
            route('home') => __('Start'),
        ];
        if (!empty($this->setup->is_admin) && $this->setup->is_admin) {
            $breadcrumbs['#'] = __('Manage');
        }

        $breadcrumbs[route($this->setup->entity_name . '.index')] = $this->setup->entity_title[1];

        $page_title = __('New :entity_title', ['entity_title' => $this->setup->entity_title[0]]);
        $breadcrumbs['#create'] = $page_title;

        return view($this->viewName('create'), [
            'page_title' => $page_title,
            'entity_name' => $this->setup->entity_name,
            'model' => $this->setup->model,
            'fields' => $this->setup->fields,
            'entity' => $entity,
            'breadcrumbs' => $breadcrumbs,
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function storeCrud(Request $request, $persist = true)
    {
        if ($persist) {
            $model = $this->setup->model::create($this->withDefaults($request->all()));
        }
        else {
            $model = (new $model_name)
                ->fill($this->withDefaults($request->all()));
        }

        if ($request->format() == 'json') {
            return [$model, $model];
        }

        return [
            $model,
            response()->redirectToRoute($this->setup->entity_name . '.index')
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
    public function editCrud(Model $entity, $options = [])
    {
        $page_title = __(
            (!empty($options['verb']) ? $options['verb'] : 'Edit')
            . ' ' . $this->stripPrefix($this->setup->entity_title[0])
        );

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

            $breadcrumbs[route($this->setup->entity_name . '.index')] = __($this->setup->entity_title[1]);
            $breadcrumbs['#edit'] = $page_title;
        }

        return view($this->viewName('edit'), [
            'page_title' => $page_title,
            'entity_name' => $this->setup->entity_name,
            'fields' => $this->setup->fields,
            'entity' => $entity,
            'action' => !empty($options['action']) ? $options['action'] : null,
            'button' => !empty($options['button']) ? $options['button'] : null,
            'breadcrumbs' => $breadcrumbs,
            'bottom' => !empty($options['bottom']) ? $options['bottom'] : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function updateCrud(Request $request, Model $entity)
    {
        $entity->update(
            $this->withDefaults($request->all())
        );

        if ($request->format() == 'json') {
            return $entity;
        }

        return response()->redirectToRoute($this->setup->entity_name . '.index')
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
    public function destroyCrud(Model $entity)
    {
        $entity->delete();
        return response()->redirectToRoute($this->setup->entity_name . '.index')
            ->with('status', __($this->stripPrefix($this->setup->entity_title[0]) . ' deleted'));
    }
}
