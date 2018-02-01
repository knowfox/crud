<?php

namespace Knowfox\Crud\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Knowfox\Crud\Requests\SettingRequest;
use Knowfox\Crud\Models\Setting;
use Knowfox\Crud\Services\Crud;

class SettingController extends Controller
{
    protected $crud;

    public function __construct(Crud $crud)
    {
        //@todo parent::__construct();
        $this->crud = $crud;
        $this->crud->setup('crud.setting');
    }

    public function index(Request $request)
    {
        return $this->crud->index($request);
    }

    public function create()
    {
        return $this->crud->create();
    }

    public function store(SettingRequest $request)
    {
        list($setting, $response) = $this->crud->store($request);
        return $response;
    }

    public function edit(Setting $setting)
    {
        return $this->crud->edit($setting);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        return $this->crud->update($request, $setting);
    }

    public function destroy(Setting $setting)
    {
        return $this->crud->destroy($setting);
    }
}
