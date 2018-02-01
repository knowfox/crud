<?php

return [
    'is_admin' => true,
    'has_create' => false,
    'model' => \Knowfox\Crud\Models\Setting::class,
    'package_name' => 'crud',
    'entity_name' => 'setting',
    'entity_title' => [' Setting', 'Settings'], // singular, plural
    'order_by' => 'name',

    'columns' => [
        'translatedName' => 'Name',
        'prettyValue' => 'Value',
        'translatedField' => 'Field Type',
    ],

    'fields' => [
        'name' => [
            'label' => 'Name',
            'type' => 'text',
        ],
        'field' => [
            'label' => 'Field Type',
            'type' => 'select',
            'options' => 'setting_types',
        ],
        'value' => [
            'label' => 'Value',
            'type' => 'setting_value',
        ],
    ],
];
