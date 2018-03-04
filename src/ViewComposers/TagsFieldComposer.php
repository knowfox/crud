<?php

namespace Knowfox\Crud\ViewComposers;

use Knowfox\Crud\Models\Setting;
use Illuminate\View\View;

class TagsFieldComposer
{
    public function compose(View $view)
    {
        $data = $view->getData();

        $options = [];

        /**
         * $options may be set explicitely ...
         */
        if (!empty($data['field']['option_values'])) {
            foreach ($data['field']['option_values'] as $id => $value) {
                $options[$id] = $value;
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