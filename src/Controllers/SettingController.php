<?php

namespace Knowfox\Crud\Controllers;

use Knowfox\Crud\Requests\SettingRequest;
use Knowfox\Crud\Models\Setting;

class SettingController extends CrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->setup = (object)[
            'is_admin' => true,
            'has_create' => false,
            'model' => Setting::class,
            'package_name' => 'crud',
            'entity_name' => 'setting',
            'entity_title' => [' Einstellung', 'Einstellungen'], // singular, plural
            'order_by' => 'name',

            'columns' => [
                'translatedName' => 'Name',
                'prettyValue' => 'Wert',
                'translatedField' => 'Feldtyp',
            ],

            'fields' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'show',
                ],
                'field' => [
                    'label' => 'Feldtyp',
                    'type' => 'select',
                    'options' => 'setting_types',
                ],
                'value' => [
                    'label' => 'Wert',
                    'type' => 'setting_value',
                ],
            ],
        ];
    }

    public function create()
    {
        return $this->createCrud();
    }

    public function store(SettingRequest $request)
    {
        list($ride, $response) = $this->storeCrud($request);
        return $response;
    }

    public function edit(Setting $setting)
    {
        return $this->editCrud($setting);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        return $this->updateCrud($request, $setting);
    }

    public function destroy(Setting $setting)
    {
        return $this->destroyCrud($setting);
    }
}
