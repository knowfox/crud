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
