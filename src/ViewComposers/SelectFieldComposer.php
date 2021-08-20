<?php

namespace Knowfox\Crud\ViewComposers;

use Knowfox\Crud\Models\Setting;
use Illuminate\View\View;

class SelectFieldComposer
{
    public function compose(View $view)
    {
        $data = $view->getData();

        $options = [];

        if (!isset($data['field']['empty']) || $data['field']['empty']) {
            $options[''] = __('-- not set --');
        }

        /**
         * $options may be set explicitely ...
         */

        // ... from a list or map ...
        $values = null;
        $is_map = false;
        if (!empty($data['field']['option_values'])) {
            $values = $data['field']['option_values'];
        }
        else
        if (!empty($data['field']['option_map'])) {
            $values = $data['field']['option_map'];
            $is_map = true;
        }
        else
        if (!empty($data['field']['option_list'])) {
            $values = $data['field']['option_list'];
        }

        if (!empty($values)) {
            if (is_callable($values)) {
                $values = $values();
            }

            if ($is_map) {
                foreach ($values as $id => $value) {
                    $options[$id] = $value;
                }
            }
            else {
                foreach ($values as $value) {
                    $options[$value] = $value;
                }
            }
        }

        /**
         * ... read from settings ...
         */
        if (!empty($data['field']['options'])) {
            $option_values = Setting::where('name', $data['field']['options'])->firstOrFail()->decodedValue;

            foreach ($option_values as $option) {
                $options[$option] = $option;
            }
        }

        /**
         * ... or be supplemented by a query
         */
        if (!empty($data['field']['model'])) {
            if (!empty($data['field']['with'])) {
                foreach ($data['field']['model']::with($data['field']['with'])->get() as $option) {
                    $options[$option->id] = $option->{$data['field']['field']};
                }
            }
            else {
                foreach ($data['field']['model']::pluck($data['field']['field'], 'id') as $id => $value) {
                    $options[$id] = $value . " ({$id})";
                }
            }
        }

        $view->with('options', $options);
    }
}